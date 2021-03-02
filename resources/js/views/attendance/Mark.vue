<template>
  <div class="app-container">
    <div class="filter-container" align="center">
      <el-row>
        <el-col :xs="12" :sm="4" :md="4">
          <el-date-picker
            v-model="listQuery.atDate"
            class="filter-item"
            type="date"
            format="dd-MM-yy"
            value-format="yyyy-MM-dd"
            @change="setSession"
          />
        </el-col>
        <el-col :xs="12" :sm="4" :md="4">
          <el-select v-model="listQuery.atSession" class="filter-item" @change="handleFilter">
            <el-option label="Morning" value="Morning" />
            <el-option label="Evening" value="Evening" />
          </el-select>
        </el-col>

        <el-col :xs="12" :sm="4" :md="4">
          <el-select v-model="listQuery.allot" default-first-option class="filter-item" placeholder="Class/Subject Alloted" @change="handleFilter">
            <el-option v-for="allot in alloted" :key="allot" :label="allot | uppercaseFirst" :value="allot" />
          </el-select>
        </el-col>
        <el-col :xs="12" :sm="4" :md="4">
          <el-input v-model="listQuery.keyword" :placeholder="$t('table.keyword')" class="filter-item" @change="handleFilter" />
        </el-col>
        <el-col :xs="12" :sm="4" :md="4">
          <el-select v-model="listQuery.search" :placeholder="$t('table.search')" clearable class="filter-item" @change="handleFilter">
            <el-option v-for="item in searchBy" :key="item" :label="item | uppercaseFirst" :value="item" />
          </el-select>
        </el-col>
        <el-col :xs="12" :sm="4" :md="4">
          <el-button :loading="downloading" class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">Search</el-button>
        </el-col>
      </el-row>
    </div>
    <div class="app-container">
      <el-row class="text-center el-button--primary pad5">
        <el-col :xs="12" :sm="12" :md="6">
          <em>Total: </em><span>{{ total }}</span>
        </el-col>
        <el-col :xs="12" :sm="12" :md="6">
          <em>Present: </em><span>{{ present }}</span>
        </el-col>
        <el-col :xs="12" :sm="12" :md="6">
          <em>Absent: </em><span>{{ absent }}</span>
        </el-col>
        <el-col :xs="12" :sm="12" :md="6">
          <em>Leave: </em><span>{{ leave }}</span>
        </el-col>
      </el-row>
    </div>
    <div class="app-container">
      <el-row class="text-right pad5">
        <el-col :xs="24" :sm="24" :md="24">
          <el-checkbox v-if="listQuery.allot" v-model="markChecked" @change="markAllPresent(seenNotify)">All Present</el-checkbox>
        </el-col>
      </el-row>
    </div>
    <el-table v-loading="listLoading" :data="list" border highlight-current-row style="width: 100%">
      <el-table-column type="expand" label=">">
        <template slot-scope="scope">
          <el-row>
            <el-col class="text-right fullDetail" :xs="24" :sm="24" :md="24">
              <h4>Notified by Subject Teachers:</h4>
              <div v-if="scope.row.notify">
                <p v-for="row in JSON.parse(scope.row.notify)" :key="row.key">{{ row }}</p>
              </div>
            </el-col>
          </el-row>
        </template>
      </el-table-column>
      <el-table-column label="Name/AdmNo.">
        <template slot-scope="scope">
          <span>{{ scope.row.fullName }}</span><br>
          <span><strong>AdNo. {{ scope.row.admsNumber }}</strong></span>
        </template>
      </el-table-column>

      <el-table-column label="CLASS | SECTION | SUBJECT">
        <template slot-scope="scope">
          <strong>{{ scope.row.class }} </strong> <strong v-if="scope.row.section">[ {{ scope.row.section }} ]</strong><br>
          <span>{{ scope.row.subject }} </span><!-- <span v-if="scope.row.subjectSections">[ {{ scope.row.subjectSections }} ]</span>-->
        </template>
      </el-table-column>
      <el-table-column label="Contact">
        <template slot-scope="scope">
          <span>{{ scope.row.contact }}</span>
        </template>
      </el-table-column>
      <el-table-column v-if="listQuery.allot" fixed="right" label="Marked" align="right">
        <template slot-scope="scope">
          <el-radio-group v-model="attendance[scope.row.at_id]" @change="atMark(scope.row.at_id,scope.row.contact,scope.row.fullName)">
            <el-radio-button title="Present" label="P" />
            <el-radio-button title="Absent" label="A" />
            <el-radio-button title="Leave" label="L" />
          </el-radio-group>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" small layout="total, prev, pager, next, jumper" :page.sync="listQuery.page" :limit.sync="listQuery.limit" :total="total" @pagination="getList" />
    <div v-if="listQuery.allot" class="filter-container" align="center">
      <el-button v-if="seenMark || checkRole(['admin','editor'])" ref="mark" :loading="downloading" class="filter-item" type="secondary" icon="el-icon-success" @click="submitMarked(0)">Mark</el-button>
      <el-button v-if="seenMarkSMS || checkRole(['admin','editor'])" ref="markSMS" :loading="downloading" class="filter-item" type="secondary" icon="el-icon-success" @click="submitMarked(1)">Mark/SMS</el-button>
      <el-button v-if="seenNotify && !checkRole(['admin','editor'])" ref="notify" :loading="downloading" class="filter-item" type="secondary" icon="el-icon-upload" @click="submitMarked(2)">Notify</el-button>
      <el-button :loading="downloading" class="filter-item" type="secondary" icon="el-icon-download" title="" @click="handleDownload" />
    </div>
  </div>
</template>

<script>

import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import Resource from '@/api/resource';
// import 'element-ui/lib/theme-chalk/display.css';
import settings from '@/api/settings';
import checkRole from '@/utils/role'; // Role checking

import { fetchUser } from '@/api/attendance';
import { atMarked } from '@/api/attendance';

const attendanceResource = new Resource('attendance');

var today = settings.today;
export default {
  name: 'AttendanceList',
  components: { Pagination },
  filters: {
    statusFilter(status) {
      const statusMap = {
        published: 'success',
        draft: 'info',
        deleted: 'danger',
      };
      return statusMap[status];
    },
  },

  data() {
    return {
      list: [],
      total: 0,
      listLoading: true,
      listQuery: settings.listQuery,
      downloading: false,
      searchBy: settings.searchBy,
      atSession: settings.listQuery.atSession,
      smsApiKey: settings['schools'][settings.myurl]['smsApiKey'],
      smsSenderId: settings['schools'][settings.myurl]['smsSenderId'],
      schoolName: settings['schools'][settings.myurl]['schoolName'],
      attendance: [],
      alloted: [],
      allotedOptions: [],
      atMarked: [],
      atSess: [],

      alTotal: 0,
      seenNotify: true,
      seenMark: true,
      seenMarkSMS: true,
      present: 0,
      absent: 0,
      leave: 0,
      SubjectWise: [],
      markChecked: false,
      notify: [],
      todayt: null,
      todayhm: '',
    };
  },
  created() {
    // console.log(this.$route.query.lookFor);
    this.listQuery.userId = this.$store.getters.userId;
    if (this.$route.query.lookFor){
      this.listQuery.allot = this.$route.query.lookFor;
      // console.log(this.listQuery.allot);
    }
    this.listQuery.atDate = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    this.todayt = today.getFullYear() + '' + (today.getMonth() + 1) + '-' + today.getDate();

    this.setSession();
    var hrs = today.getHours();
    this.todayhm = today.getHours() + ':' + today.getMinutes();
    //console.log(this.todayhm);
    if (hrs < 12) {
      this.atSession = 'Morning';
    } else if (hrs >= 12) {
      this.atSession = 'Evening';
    }

    this.listQuery.atSession = this.atSession;
    this.getUser(this.$store.getters.userId);
  },
  methods: {
    markAllPresent(){
      // console.log(this.atSess);

      const refList = this.list; this.list = [];
      refList.forEach(element => {
        // console.log(element);
        if (this.markChecked){
          this.attendance[element.at_id] = 'P';// element[0][0][this.atSession];
        } else {
          this.attendance[element.at_id] = 'O';// element[0][0][this.atSession];
        }
        this.list.push(element);
        this.atMark(element.at_id, element.contact, element.fullName);

        if (!this.alMarked.includes(element.at_id)){
          this.alMarked.push(element.at_id);
          this.alTotal++;
        }
      });
    },
    submitMarked(n){
      // console.log(this.atMarked.length+" "+(this.total - this.alTotal));
      // if(this.atMarked.length<(this.total-this.alTotal)){
      this.$confirm('Are you sure to mark attendance of (' + (this.atMarked.length > this.alTotal ? this.alTotal : this.atMarked.length) + ') students..?', 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning',
      }).then(() => {
        atMarked(this.atMarked, n, this.smsApiKey, this.smsSenderId, this.schoolName)
          .then(response => {
            // console.log(response.data);
            if (n == 2){
              this.$message({
                type: 'success',
                message: 'Attendance notified to concerned faculty..!',

              });
              // this.resetVals();
            } else {
              this.$message({
                type: 'success',
                message: 'Attendance marked successfully..!',
              });
              this.resetVals();
              this.getList();
            }
          });
      }).catch(() => {
        // console.log(this.atMarked);
        this.$message({
          type: 'info',
          message: 'Attendance cancelled..!',
        });
        this.resetVals();
        this.getList();
      });
      // } else atMarked(this.atMarked);

      // console.log(this.atMarked);
    },
    atMark(at_id, contact, name){
      const myindex = 0;

      let flag = false; let index = 0;
      const myclass = this.listQuery.allot.split(' | ');

      // console.log(this.SubjectWise[at_id]);
      // console.log(at_id+" "+contact+" "+name);
      if (this.SubjectWise[at_id]){
        this.SubjectWise[at_id].forEach(element => {
          if (element.substr(0, this.listQuery.allot.length) == this.listQuery.allot){
            flag = true;

            if (parseInt(myclass[0]) > settings['schools'][settings.myurl]['sections']['lg']) {
              this.SubjectWise[at_id][index] = this.listQuery.allot + ' | ' + this.attendance[at_id];
            }
          }
          index++;
        });
        if (!flag) {
          if (parseInt(myclass[0]) > settings['schools'][settings.myurl]['sections']['lg']) {
            this.SubjectWise[at_id].push(this.listQuery.allot + ' | ' + this.attendance[at_id]);
          }
        }
        // console.log(this.SubjectWise[at_id]);

        if (this.listQuery.atSession == 'Morning'){
          // console.log(at_id+" "+contact+" "+name);

          this.notify[at_id].push(
            {
              'Period': this.todayhm,
              'Morning': this.attendance[at_id],
              'Evening': this.atSess[at_id][0][0]['Evening'],
              'SubjectWise': this.SubjectWise[at_id],
            });
          this.atMarked.push([
            at_id,
            {
              'Morning': this.attendance[at_id],
              'Evening': this.atSess[at_id][0][0]['Evening'],
              'SubjectWise': this.SubjectWise[at_id],
            },
            contact,
            this.listQuery.atSession,
            name,
            this.notify[at_id],
          ]);
        } else {
          // console.log(at_id+" "+contact+" "+name);

          this.notify[at_id].push({
            'Period': this.todayhm,
            'Morning': this.atSess[at_id][0][0]['Morning'],
            'Evening': this.attendance[at_id],
            'SubjectWise': this.SubjectWise[at_id],
          });
          this.atMarked.push([
            at_id,
            {
              'Morning': this.atSess[at_id][0][0]['Morning'],
              'Evening': this.attendance[at_id],
              'SubjectWise': this.SubjectWise[at_id],
            },
            contact,
            this.listQuery.atSession,
            name,
            this.notify[at_id],

          ]);
        }
      }
      // console.log(this.atMarked);
      if (!this.alMarked.includes(at_id)){
        this.alMarked.push(at_id);
        this.alTotal++;
      }
    },
    async getUser(id) {
      fetchUser(this.$store.getters.userId)
        .then(response => {
          const allot = JSON.parse(response.data[0]['alloted']);
          const allotedOptions = JSON.parse(response.data[0]['allotedOptions']);

          if (allot && allot.length || checkRole(['admin', 'editor'])){
            this.listQuery.allot = this.$route.query.lookFor ? this.$route.query.lookFor : allot[0];

            allot.forEach(element => {
              this.alloted.push(element);
            });
            allotedOptions.forEach(element => {
              this.allotedOptions.push(element);
            });
            // console.log(this.allotedOptions);
            this.getList();
          }
          this.listLoading = false;
        });
    },
    checkRole,
    setSession: function() {
      if (this.listQuery.atDate){
        var admDate = this.listQuery.atDate.split('-');
        if (parseInt(admDate[1]) >= 2){
          this.listQuery.session = admDate[0] + '-' + (parseInt(admDate[0]) + 1);
        } else {
          this.listQuery.session = parseInt(admDate[0]) - 1 + '-' + (parseInt(admDate[0]));
        }
      } else {
        this.listQuery.atDate = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
      }
      // this.getList();
      // console.log(this.listQuery.session);
    },
    async getList() {
      this.alMarked = []; this.alTotal = 0;
      if (this.allotedOptions.includes('Can Mark Attendance of ' + this.listQuery.allot)){
        this.seenMark = true;
        this.seenMarkSMS = true;
        this.seenNotify = false;
      } else if (this.allotedOptions.includes('Can Notify Attendance of ' + this.listQuery.allot)){
        this.seenMark = false;
        this.seenMarkSMS = false;
        this.seenNotify = true;
      }
      this.listLoading = true;

      const { data } = await attendanceResource.list(this.listQuery);
      this.list = []; this.atMarked = []; this.atSess = []; this.attendance = []; this.SubjectWise = [];
      this.atSession = this.listQuery.atSession; this.alTotal = 0;
      if (data.records.length){
        data.records.forEach(element => {
          this.atSess[element.at_id] = [JSON.parse(element.attendance)];
          if (JSON.parse(element.notify).length){
            this.notify[element.at_id] = JSON.parse(element.notify);
          } else {
            this.notify[element.at_id] = new Array();
            this.notify[element.at_id][this.todayhm] = [];
          }
          // console.log(element.at_id+":"+element.notify);
          if (this.atSess[element.at_id][0][0][this.atSession]){
            this.attendance[element.at_id] = this.atSess[element.at_id][0][0][this.atSession];

            if (this.attendance[element.at_id] != 'O' && this.attendance[element.at_id] != ''){
              this.markChecked = false;
              if (!this.alMarked.includes(element.at_id)){
                this.alMarked.push(element.at_id);
                this.alTotal++;
              }
            } /* else {
              this.attendance[element.at_id]="P";
              this.atMark(element.at_id, element.contact, element.fullName);
              if(!this.alMarked.includes(element.at_id)){
                this.alMarked.push(element.at_id);
                this.alTotal++;
              }
            }*/
          }

          this.SubjectWise[element.at_id] = this.atSess[element.at_id][0][0]['SubjectWise'] ? this.atSess[element.at_id][0][0]['SubjectWise'] : [];
          const index = this.alloted.indexOf(this.listQuery.allot); const indx = 0;
          // console.log(this.SubjectWise[element.at_id]);
          // this.SubjectWise[element.at_id].forEach(elemen=>{

          if (this.SubjectWise[element.at_id] && this.SubjectWise[element.at_id][index]){
            // console.log(index+" "+this.SubjectWise[element.at_id][index]);
            const subjwise = this.SubjectWise[element.at_id][index].split(' | ');

            if (subjwise[3]){
              this.attendance[element.at_id] = subjwise[3];
              // console.log(subjwise[3]);
            }
          } // else this.attendance[element.at_id]=;
          // });
          this.list.push(element);
          if (!this.alTotal) {
            this.seenNotify = false;
          }
        });
        // console.log(this.SubjectWise);
        // console.log(this.attendance);
        this.total = data.total;
        this.present = data.present;
        this.absent = data.absent;
        this.leave = data.leave;
      }
      this.listLoading = false;
    },
    handleFilter() {
      this.downloading = true;
      this.listQuery.page = 1;

      if (this.listQuery.allot && this.listQuery.allot.length || checkRole(['admin', 'editor'])){
        this.getList();
      }
      // this.getList();
      this.downloading = false;
    },
    handleDownload() {
      this.downloading = true;
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['ID', 'UID', 'Name', 'Class', 'Section', 'Admission Number'];
        const filterVal = ['st_id', 'uid', 'fullName', 'class', 'section', 'admsNumber'];
        const data = this.formatJson(filterVal, this.list);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'student-list',
        });
        this.downloading = false;
      });
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]));
    },
    resetVals(){
      this.list = [];
      this.total = 0;
      this.listLoading = true;
      this.listQuery = settings.listQuery;
      this.downloading = false;
      this.attendance = [];
      this.atMarked = [];
      this.atSess = [];

      this.alTotal = 0;
      this.seenNotify = true;
      this.seenMark = true;
      this.seenMarkSMS = true;
      this.present = 0;
      this.absent = 0;
      this.leave = 0;
      this.SubjectWise = [];
      this.markChecked = false;
    },

  },
};
</script>

<style>
.pad3 {
  padding: 3px;
}
.pad5 {
  padding: 5px;
}
col[name="el-table_1_column_2"] {
  width: 200px;
}
col[name="el-table_1_column_3"] {
  width: 400px;
}
col[name="el-table_1_column_4"] {
  width: 150px;
}
.text-right {
  text-align: right;
}
.text-white {
  color: #fffff4;
}
@media screen and (max-width: 480px) {
  col[name="el-table_1_column_2"],
  col[name="el-table_1_column_3"] {
    width: 200px;
  }
  /*col[name="el-table_1_column_1"],
  .el-table_1_column_1,*/
  col[name="el-table_1_column_6"],
  .el-table_1_column_6,
  .fullDetail {
    display: none;
  }
}

</style>
