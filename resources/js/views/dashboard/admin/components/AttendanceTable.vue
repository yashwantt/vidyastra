<template>

  <el-table
    v-loading="loading"
    :data="list"
    style="width: 100%;padding: 3px;"
  >
    <el-table-column label="Class" min-width="200">
      <template slot-scope="scope">
        <a style="text-decoration:blink" target="_blank" :href="'/#/attendance/mark?lookFor='+scope.row.class+''">
          {{ scope.row && scope.row.class }}</a>
      </template>
    </el-table-column>
    <el-table-column label="Total" align="center">
      <template slot-scope="scope">
        {{ scope.row && scope.row.total }}
      </template>
    </el-table-column>
    <el-table-column label="Present" align="center">
      <template slot-scope="scope">
        {{ scope.row && scope.row.present }}
      </template>
    </el-table-column>
    <el-table-column label="Absent" align="center">
      <template slot-scope="scope">
        {{ scope.row && scope.row.absent }}
      </template>
    </el-table-column>
    <el-table-column label="Leave" align="center">
      <template slot-scope="scope">
        {{ scope.row && scope.row.leave }}
      </template>
    </el-table-column>
  </el-table>

</template>

<script>
import { fetchList } from '@/api/order';
import settings from '@/api/settings';
import Resource from '@/api/resource';
const attendanceResource = new Resource('attendance');

export default {
  filters: {
    statusFilter(status) {
      const statusMap = {
        success: 'success',
        pending: 'danger',
      };
      return statusMap[status];
    },
    orderNoFilter(str) {
      return str;
    },
  },
  data() {
    return {
      list: [],
      loading: true,
      classes: [],
      listQuery: settings.listQuery,
    };
  },
  created() {
    this.listQuery.allot = '';
    this.fetchData();
  },
  methods: {
    async fetchData() {
      this.list = [];
      const { data } = await attendanceResource.list(this.listQuery);
      const listArr = [];
      if (data){
        if (data.records.length){
          const classes = [];
          data.records.forEach(element => {
            const session = JSON.parse(element.attendance);
            const myclass = JSON.parse(element.class);
            const section = element.section;
            const subjects = JSON.parse(element.subjectSections);

            // console.log(session[0]['SubjectWise']);
            // let subjectSections=JSON.parse(element.subjectSections);
            if (parseInt(myclass[0]) > settings['schools'][settings.myurl]['sections']['lg']){
              subjects.forEach(elemen => { // subjectSections
                if (elemen != 'All'){
                  // settings['schools'][settings.myurl]['sections']['gt'].forEach(eleme=>{ //subjectSections
                  // if(eleme!="NA"){
                  if (!classes.includes(myclass[0] + ' | ' + elemen)){
                    classes.push(myclass[0] + ' | ' + elemen);
                    // console.log("Atnd: "+session[0]['SubjectWise']);
                    listArr.push({
                      'class': myclass[0] + ' | ' + elemen,
                      'total': 1,
                      'present': session[0]['SubjectWise'].includes(myclass[0] + ' | ' + elemen + ' | P') ? 1 : 0,
                      'absent': session[0]['SubjectWise'].includes(myclass[0] + ' | ' + elemen + ' | A') ? 1 : 0,
                      'leave': session[0]['SubjectWise'].includes(myclass[0] + ' | ' + elemen + ' | L') ? 1 : 0,
                    });
                  } else {
                    const index = classes.indexOf(myclass[0] + ' | ' + elemen);
                    // console.log(listArr[index].total+" "+classes.indexOf(element.class+" | "+elemen));
                    listArr[index].total++;
                    if (session[0]['SubjectWise'].includes(myclass[0] + ' | ' + elemen + ' | P')) {
                      listArr[index].present++;
                    } else if (session[0]['SubjectWise'].includes(myclass[0] + ' | ' + elemen + ' | A')) {
                      listArr[index].absent++;
                    } else if (session[0]['SubjectWise'].includes(myclass[0] + ' | ' + elemen + ' | L')) {
                      listArr[index].leave++;
                    }
                  }
                  // }
                  // });
                }
              });
            } else {
              settings['schools'][settings.myurl]['sections']['lt'].forEach(elemen => { // subjectSections
                if (elemen != 'NA') {
                  if (!classes.includes(myclass[0] + ' | ' + elemen) && section == elemen){
                    classes.push(myclass[0] + ' | ' + elemen);
                    // console.log("Atnd:"+session[0][settings.atSession]);
                    // console.log("Class: "+myclass[0]+" | "+elemen+", Att: "+session[0][settings.atSession])
                    listArr.push({
                      'class': myclass[0] + ' | ' + elemen,
                      'total': 1,
                      'present': session[0][settings.atSession] == 'P' ? 1 : 0,
                      'absent': session[0][settings.atSession] == 'A' ? 1 : 0,
                      'leave': session[0][settings.atSession] == 'L' ? 1 : 0,
                    });
                  } else if (section == elemen){
                    const index = classes.indexOf(myclass[0] + ' | ' + elemen);
                    // console.log(listArr[index].total+" "+classes.indexOf(element.class+" | "+elemen));
                    // console.log(element.class+" | "+elemen);
                    // console.log("Class: "+myclass[0]+" | "+elemen+", Att: "+session[0][settings.atSession])
                    listArr[index].total++;
                    if (session[0][settings.atSession] == 'P') {
                      listArr[index].present++;
                    } else if (session[0][settings.atSession] == 'A') {
                      listArr[index].absent++;
                    } else if (session[0][settings.atSession] == 'L') {
                      listArr[index].leave++;
                    }
                  }
                }
              });
            }
          });
        }
      }
      this.list = listArr;
      // console.log(this.list);
      this.loading = false;
    },
  },
};
</script>
