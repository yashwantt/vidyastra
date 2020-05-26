<template>
  <el-row :gutter="40" class="panel-group">
    <el-col :xs="24" :sm="12" :lg="6" class="card-panel-col">
      <div class="card-panel">
        <div class="card-panel-icon-wrapper icon-students">
          <a href="/#/students/list">
            <svg-icon icon-class="peoples" class-name="card-panel-icon" />
          </a>
        </div>
        <div class="card-panel-description">
          <div class="card-panel-text">
            <a href="/#/students/list">
              Students
            </a>
          </div>
          <a href="/#/students/list">
            <count-to :start-val="1" :end-val="students" :duration="3000" class="card-panel-num" />
          </a>
        </div>
      </div>
    </el-col>
    <el-col :xs="24" :sm="12" :lg="6" class="card-panel-col">
      <div class="card-panel">
        <div class="card-panel-icon-wrapper icon-present">
          <svg-icon icon-class="peoples" class-name="card-panel-icon" />
        </div>
        <div class="card-panel-description">
          <div class="card-panel-text">
            Present
          </div>
          <count-to :start-val="0" :end-val="present" :duration="3000" class="card-panel-num" />
        </div>
      </div>
    </el-col>
    <el-col :xs="24" :sm="12" :lg="6" class="card-panel-col">
      <div class="card-panel">
        <div class="card-panel-icon-wrapper icon-absent">
          <svg-icon icon-class="peoples" class-name="card-panel-icon" />
        </div>
        <div class="card-panel-description">
          <div class="card-panel-text">
            Absent
          </div>
          <count-to :start-val="0" :end-val="absent" :duration="3200" class="card-panel-num" />
        </div>
      </div>
    </el-col>
    <el-col :xs="24" :sm="12" :lg="6" class="card-panel-col">
      <div class="card-panel">
        <div class="card-panel-icon-wrapper icon-leave">
          <svg-icon icon-class="peoples" class-name="card-panel-icon" />
        </div>
        <div class="card-panel-description">
          <div class="card-panel-text">
            On Leave
          </div>
          <count-to :start-val="0" :end-val="leave" :duration="3600" class="card-panel-num" />
        </div>
      </div>
    </el-col>
  </el-row>
</template>

<script>
import CountTo from 'vue-count-to';
import Resource from '@/api/resource';
import settings from '@/api/settings';

const studentResource = new Resource('students');
var today = new Date();
export default {
  components: {
    CountTo,
  },
  data(){
    return {
      students: 0,
      present: 0,
      absent: 0,
      leave: 0,
      listQuery: settings.listQuery,
    };
  },
  created(){
    this.listQuery.atDate = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    var admDate = this.listQuery.atDate.split('-');
    if (parseInt(admDate[1]) >= 2){
      this.listQuery.session = admDate[0] + '-' + (parseInt(admDate[0]) + 1);
    } else {
      this.listQuery.session = parseInt(admDate[0]) - 1 + '-' + (parseInt(admDate[0]));
    }
    // console.log(this.listQuery.session);
    this.getList();
  },
  methods: {
    //
    async getList() {
      const { data } = await studentResource.list(this.listQuery);
      // console.log(data.records);
      // console.log("Present: "+data.present);
      this.students = data.total;
      this.present = data.present;
      this.absent = data.absent;
      this.leave = data.leave;
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.panel-group {
  margin-top: 18px;
  .card-panel-col{
    margin-bottom: 32px;
  }
  .card-panel {
    height: 108px;
    cursor: pointer;
    font-size: 12px;
    position: relative;
    overflow: hidden;
    color: #666;
    background: #fff;
    box-shadow: 4px 4px 40px rgba(0, 0, 0, .05);
    border-color: rgba(0, 0, 0, .05);
    &:hover {
      .card-panel-icon-wrapper {
        color: #fff;
      }
      .icon-people {
         background: #40c9c6;
      }
      .icon-message {
        background: #36a3f7;
      }
      .icon-money {
        background: #f4516c;
      }
      .icon-shopping {
        background: #34bfa3
      }
    }
    .icon-present {
      color:green;
    }
    .icon-absent {
      color: red;
    }
    .icon-leave {
      color: yellow;
    }
    .icon-students {
      color: #36a3f7;
    }

    .card-panel-icon-wrapper {
      float: left;
      margin: 14px 0 0 14px;
      padding: 16px;
      transition: all 0.38s ease-out;
      border-radius: 6px;
    }
    .card-panel-icon {
      float: left;
      font-size: 48px;
    }
    .card-panel-description {
      float: right;
      font-weight: bold;
      margin: 26px;
      margin-left: 0px;
      .card-panel-text {
        line-height: 18px;
        color: rgba(0, 0, 0, 0.45);
        font-size: 16px;
        margin-bottom: 12px;
      }
      .card-panel-num {
        font-size: 20px;
      }
    }
  }
}
</style>
