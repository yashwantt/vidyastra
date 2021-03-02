<template>
  <div class="app-container">
    <div class="filter-container" align="center">
      <el-row>
        <el-col :xs="12" :sm="4" :md="4">
          <el-input
            v-model="listQuery.keyword"
            :placeholder="$t('table.keyword')"
            class="filter-item"
            clearable
            @change="handleFilter"
          />
        </el-col>
        <el-col :xs="12" :sm="4" :md="4">
          <el-select v-model="listQuery.search" :placeholder="$t('table.search')" clearable class="filter-item" @change="handleFilter">
            <el-option v-for="item in searchBy" :key="item" :label="item | uppercaseFirst" :value="item" />
          </el-select>
        </el-col>

        <el-col :xs="12" :sm="8" :md="8">
          <el-select v-model="listQuery.session" placeholder="Session" clearable class="filter-item" @change="handleFilter">
            <el-option v-for="sess in sessions" :key="sess['session']" :label="sess['session']" :value="sess['session']" />
          </el-select>
          <el-button :loading="downloading" class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">Search</el-button>
        </el-col>
        <el-col :xs="12" :sm="4" :md="4">
          <el-button :loading="downloading" class="filter-item" type="secondary" icon="el-icon-download" @click="handleDownload" />
        </el-col>
      </el-row>
    </div>
    <el-table v-loading="listLoading" :data="list" border highlight-current-row style="width: 100%">
      <el-table-column type="expand" label=">">
        <template slot-scope="scope">
          <el-row>
            <el-col :xs="24" :sm="12" :md="6">
              <img v-if="scope.row.image" ref="myimg" :src="scope.row.image" class="avatar">
            </el-col>
            <el-col :xs="24" :sm="12" :md="6">
              <p v-if="scope.row.father"><strong>Father: </strong>{{ scope.row.father }}</p>
              <p v-if="scope.row.mother"><strong>Mother: </strong>{{ scope.row.mother }}</p>
              <p v-if="scope.row.occupation"><strong>Occupation: </strong>{{ scope.row.occupation }}</p>
            </el-col>
          </el-row>
        </template>
      </el-table-column>
      <el-table-column label="Name/UID No." width="200px">
        <template slot-scope="scope">
          <span>{{ scope.row.fullName }}</span><br>
          <span><strong>UID No. {{ scope.row.uid }}</strong></span>
        </template>
      </el-table-column>

      <el-table-column label="Class|Subject">
        <template slot-scope="scope">
          <strong>{{ scope.row.class }} </strong><br>
          <span>{{ scope.row.subject }} </span><!--<span v-if="scope.row.subjectSections">[ {{ scope.row.subjectSections }} ]</span>-->
        </template>
      </el-table-column>
      <el-table-column label="Contact" width="150px">
        <template slot-scope="scope">
          {{ scope.row.contact }}
        </template>
      </el-table-column>
      <el-table-column
        v-if="checkRole(['admin','editor'])"
        label="Show/Delete"
        class="hidden-sm-and-down"
        width="125px"
      >
        <template slot-scope="scope">
          <router-link :to="'/students/edit-enquiry/'+scope.row.st_id+'/enq'">
            <el-button size="mini" type="primary"><i class="el-icon-document" /></el-button>
          </router-link>
          <el-button
            v-if="scope.row.status!='active'"
            size="mini"
            type="primary"
            @click="handleDelete(scope.row)"
          ><i class="el-icon-delete" /></el-button>
        </template>
      </el-table-column>
    </el-table>
    <pagination v-show="total>0" small :page.sync="listQuery.page" :limit.sync="listQuery.limit" :total="total" @pagination="getList" />

  </div>
</template>

<script>

import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import Resource from '@/api/resource';
// import 'element-ui/lib/theme-chalk/display.css';
import settings from '@/api/settings';
import checkRole from '@/utils/role'; // Role checking
import axios from 'axios';

// const studentResource = new Resource('students');

var today = settings.today;
export default {
  name: 'StudentList',
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
      alTotal: 0,
      sessions: settings.sessions,
    };
  },
  created() {
    this.listQuery.atDate = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    this.setSession();
    var hrs = today.getHours();
    this.getList();
  },
  methods: {
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
      this.listLoading = true;
      //console.log(this.listQuery);
      this.listQuery.imon = 'sList';
      // const { data } = await studentResource.list(this.listQuery);
      axios
        .get('/api/enquiries', {
          params: this.listQuery,
        }).then(response => {
          // console.log(response.data.data);
          var data = response.data.data;
          this.list = [];
          this.sessions = data['sessions'];
          if (data.records.length){
            this.total = data.total;
            data.records.forEach(element => {
              this.list.push(element);
            });
          }
          this.listLoading = false;
        }).catch(error => {
          console.log(error);
        });
      // console.log(data);
    },
    handleFilter() {
      this.downloading = true;
      this.getList();
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
    handleDelete(row) {
      this.$confirm('Are you sure to delete..?', 'Warning', {
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        type: 'warning',
      }).then(() => {
        axios
          .get('/api/enquiries/' + row.st_id + '/0', {}).then(response => {
            this.msg = 'Records removed.';
            console.log('Record removed');
          }).catch(error => {
            console.log(error);
          });

        const index = this.list.indexOf(row);

        this.list.splice(index, 1);
      }).catch(err => {
        console.log(err);
      });
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]));
    },

  },
};
</script>

<style scoped>

@media screen and (max-width: 480px) {
  col[name="el-table_1_column_2"],
  col[name="el-table_1_column_3"] {
    width: 200px;
  }
  col[name="el-table_1_column_1"],
  .el-table_1_column_1,
  col[name="el-table_1_column_6"],
  .el-table_1_column_6 {
    display: none;
  }
  .avatar {
    width: 150px;
    height: auto;
    display: block;
  }
}
</style>
