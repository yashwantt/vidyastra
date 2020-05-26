<template>
  <div class="app-container">
    <div id="wrap">
      <el-row>
        <el-col :md="24">
          <h1>{{ schoolName }}</h1>
          <h2>{{ schoolAddress }}</h2>
          <el-row>
            <el-col :md="12" class="text-right">
              <strong>UDISE CODE: <div class="formVal">{{ schoolUdCode }}</div>&nbsp;&nbsp;|</strong>
            </el-col>
            <el-col :md="12">
              <strong>&nbsp;&nbsp;DEPTT. CODE: <div class="formVal">{{ schoolDeptCode }}</div></strong>
            </el-col>
          </el-row>
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="6"><br>
          <strong>File No.:  <div class="formVal">{{ admissionForm.slcFileNo }}</div></strong>
        </el-col>
        <el-col :md="12">
          <h2><span style="border: 1px solid; padding: 3px 15px;">SCHOOL LEAVING CERTIFICATE</span></h2>
          <h3>Academic Year: <div class="formVal">{{ admissionForm.session }}</div></h3>
        </el-col>
        <el-col :md="6"><br>
          <strong>Issue Date:  <div class="formVal">{{ admissionForm.issueDate.split("-")[2] }}-{{ admissionForm.issueDate.split("-")[1] }}-{{ admissionForm.issueDate.split("-")[0] }}</div></strong>
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="10">
          <label>Pupil's Name </label> : <div class="formVal">{{ admissionForm.fullName }} ({{ admissionForm.gender }})</div> &nbsp;
        </el-col>
        <el-col :md="14">
          <label>Admission No</label> : <div class="formVal">{{ admissionForm.admsNumber }}</div>
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="10">
          <label>Date of Birth </label> :<div class="formVal">{{ admissionForm.dob }}</div>&nbsp;&nbsp;
        </el-col>
        <el-col :md="14">
          <label>Aadhar/Uid</label> : <div class="formVal">{{ admissionForm.uid }}</div>&nbsp;
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="10">
          <label>Father's Name</label> : <div class="formVal">{{ admissionForm.father }}</div>
        </el-col>
        <el-col :md="7">
          <label>Occupation </label> : <div class="formVal">{{ admissionForm.occupation }}</div>
        </el-col>
        <el-col :md="7">
          <label>Mother's Name</label> : <div class="formVal">{{ admissionForm.mother }}</div>
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="24">
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>CERTIFIED </strong>that <div class="formVal">{{ admissionForm.fullName }}</div>, S/o/D/o <div class="formVal">{{ admissionForm.father }}</div> attended this school up-to <div class="formVal">
            {{ this.admissionForm.slcPeriodTo.split('-')[2] }}-{{ this.admissionForm.slcPeriodTo.split('-')[1] }}-{{ this.admissionForm.slcPeriodTo.split('-')[0] }}
          </div>. He/she has paid all the sums due to school, and was allowed on above date to withdraw his/her name. He/she was reading in Class <div class="formVal"><span v-if="admissionForm['class'][0]">{{ admissionForm['class'][0] }}</span> <span v-if="admissionForm['class'][1]">{{ admissionForm['class'][1] }}</span>.
          </div>
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="24">
          <h4>He/she was examined in <u><span v-if="admissionForm['class'][0]">{{ admissionForm['class'][0] }}</span> <span v-if="admissionForm['class'][1]">{{ admissionForm['class'][1] }}</span></u> and </h4>
          <ul>
            <li>{{ admissionForm.slcRemarks }}</li>
          </ul>
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="24">
          <p><strong>The following particulars are certified to be correct according to the registers of this school and the certificate(s) produced from previous school(s) attended during school year:</strong></p>
          <el-table border :data="admissionForm.slcData" stripe>
            <el-table-column type="index" :xs="24" :md="1" label="#" />
            <el-table-column :xs="24" :md="4" prop="slcSchool" label="School" />
            <el-table-column :xs="24" :sm="12" :md="3" prop="slcDOA" label="D.O.A">
              <template slot-scope="scope">
                {{ scope.row.slcDOA.split("-")[2] }}-{{ scope.row.slcDOA.split("-")[1] }}-{{ scope.row.slcDOA.split("-")[0] }}
              </template>
            </el-table-column>
            <el-table-column :xs="24" :sm="12" :md="3" prop="slcDOW" label="D.O.W">
              <template slot-scope="scope">
                {{ scope.row.slcDOW.split("-")[2] }}-{{ scope.row.slcDOW.split("-")[1] }}-{{ scope.row.slcDOW.split("-")[0] }}
              </template>
            </el-table-column>
            <el-table-column :xs="24" :sm="12" :md="3" prop="slcPeriodFrom" label="Period From">
              <template slot-scope="scope">
                {{ scope.row.slcPeriodFrom.split("-")[2] }}-{{ scope.row.slcPeriodFrom.split("-")[1] }}-{{ scope.row.slcPeriodFrom.split("-")[0] }}
              </template>
            </el-table-column>
            <el-table-column :xs="24" :sm="12" :md="3" prop="slcPeriodTo" label="Period To">
              <template slot-scope="scope">
                {{ scope.row.slcPeriodTo.split("-")[2] }}-{{ scope.row.slcPeriodTo.split("-")[1] }}-{{ scope.row.slcPeriodTo.split("-")[0] }}
              </template>
            </el-table-column>
            <el-table-column :xs="24" :sm="12" :md="2" prop="slcAtTotal" title="Possible Attendance" label="Total" />
            <el-table-column :xs="24" :sm="12" :md="1" prop="slcAttended" label="Present" />
            <el-table-column :xs="24" :sm="12" :md="1" prop="slcLeave" label="Leave" />
          </el-table>
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="12"><br><br>
          <strong>Date of Issue: <div class="formVal">{{ admissionForm.issueDate.split("-")[2] }}-{{ admissionForm.issueDate.split("-")[1] }}-{{ admissionForm.issueDate.split("-")[0] }}</div></strong>
        </el-col>
        <el-col :md="12" class="text-right"><br><br>
          <strong>Signature & Seal of Head of School/Institution&nbsp;&nbsp;</strong>
        </el-col>
      </el-row>
    </div>
  </div>
</template>

<script>
import settings from '@/api/settings.js';
import { fetchStudent } from '@/api/student';

export default {
  props: {
    isEdit: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      admissionForm: {
        image: '',
        st_id: null, ad_id: null, ac_id: null,

        doa: '', session: '', type: '', number: '', class: '', section: '', cwsn: '',
        subject: ['All'], subjectSections: [],

        uid: '', dob: '', gender: 'Boy', fullName: '', father: '',
        mother: '', occupation: '', income: '', contact: '', email: '', addressLine: [], country: 'India',
        state: ['Himachal Pradesh'], city: [], pinCode: [], category: '', subCategory: '', religion: '',
        locality: 'Amit Apartment',

        bankName: '', bankAccount: '', bankIfsc: '', bankBranch: '',

        achYear: '', achClass: '', achRollNumber: '', achMarks: '', achScored: '', achBoard: '',
        achResult: '', prevClassResult: [],

        slcSchool: 'This School', slcDOA: '', slcDOW: '', slcPeriodFrom: '', slcPeriodTo: '', slcAtTotal: '',
        slcAttended: '', slcLeave: '', slcData: [], slcRemarks: '', issueDate: '', slcFileNo: '', admsStatus: 'active',
      },
      sno: 1,
      schoolName: settings['schools'][settings.myurl]['schoolName'],
      schoolAddress: settings['schools'][settings.myurl]['schoolAddress'],
      schoolUdCode: settings['schools'][settings.myurl]['udiseCode'],
      schoolDeptCode: settings['schools'][settings.myurl]['depttSchoolCode'],
      classes: settings['schools'][settings.myurl]['classes'],

    };
  },
  tempRoute: {},
  created(){
    var today = settings.today;
    const id = this.$route.params && this.$route.params.id;
    const ad_id = this.$route.params && this.$route.params.ad_id;

    this.admissionForm.doa = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    var admDate = this.admissionForm.doa.split('-');
    if (parseInt(admDate[1]) >= 2){
      this.admissionForm.session = admDate[0] + '-' + (parseInt(admDate[0]) + 1);
    } else {
      this.admissionForm.session = parseInt(admDate[0]) - 1 + '-' + (parseInt(admDate[0]));
    }
    this.fetchData(id, this.admissionForm.session, ad_id);

    this.tempRoute = Object.assign({}, this.$route);
  },
  methods: {
    fetchData(id, thisSession, ad_id) {
      fetchStudent(id, thisSession, ad_id, true)
        .then(response => {
          response.data[0].forEach(element => {
            this.admissionForm.ad_id = element.ad_id;

            this.admissionForm.doa = element.doa;
            this.admissionForm.session = element.session;
            this.admissionForm.type = element.type;
            this.admissionForm.admsNumber = element.admsNumber;
            this.admissionForm.class = JSON.parse(element.class) ? JSON.parse(element.class) : [];
            this.admissionForm.section = element.section;
            this.admissionForm.cwsn = element.cwsn;
            this.admissionForm.subject = JSON.parse(element.subject) ? JSON.parse(element.subject) : [];
            this.admissionForm.subjectSections = JSON.parse(element.subjectSections) ? JSON.parse(element.subjectSections) : [];

            this.admissionForm.st_id = element.st_id;
            this.admissionForm.uid = element.uid;
            const dob = element.dob.split('-');
            this.admissionForm.dob = dob[2] + '-' + dob[1] + '-' + dob[0];
            this.admissionForm.gender = element.gender;
            this.admissionForm.fullName = element.fullName;
            this.admissionForm.father = element.father;
            this.admissionForm.mother = element.mother;
            this.admissionForm.occupation = element.occupation;
            this.admissionForm.income = element.income;
            this.admissionForm.contact = element.contact;
            this.admissionForm.email = element.email;

            this.admissionForm.addressLine = JSON.parse(element.addressLine) ? JSON.parse(element.addressLine) : [];
            this.admissionForm.country = element.country;
            this.admissionForm.state = JSON.parse(element.state) ? JSON.parse(element.state) : [];
            this.admissionForm.city = JSON.parse(element.city) ? JSON.parse(element.city) : [];
            this.admissionForm.pinCode = JSON.parse(element.pinCode) ? JSON.parse(element.pinCode) : [];

            this.admissionForm.category = element.category;
            this.admissionForm.subCategory = element.subCategory;
            this.admissionForm.religion = element.religion;
            this.admissionForm.locality = element.locality;

            this.admissionForm.bankName = element.bankName;
            this.admissionForm.bankAccount = element.bankAccount;
            this.admissionForm.bankIfsc = element.bankIfsc;
            this.admissionForm.bankBranch = element.bankBranch;
            this.admissionForm.image = element.image;
            this.admissionForm.ac_id = element.ac_id;
            // this.admissionForm.prevClassResult=JSON.parse(element.prevClassResult);
            this.admissionForm.slcData = JSON.parse(element.slcData) ? JSON.parse(element.slcData) : [];
            // console.log(element);
            this.admissionForm.slcRemarks = element.slcRemarks;
            this.admissionForm.issueDate = element.issueDate;

            this.admissionForm.slcFileNo = element.slcFileNo;
            this.admissionForm.admsStatus = element.admsStatus;
          });
          this.admissionForm.slcPeriodFrom = response.data[1][0].atDate;
          this.admissionForm.slcPeriodTo = response.data[1][response.data[1].length - 1].atDate;

          setTimeout(() => {
            this.fullscreenLoading = false;
            this.$nextTick(() => {
              // window.print();
              // window.close();
            });
          }, 3000);
        })
        .catch(err => {
          console.log(err);
        });
    },
  },
};
</script>

<style>
  /* css For Menu */

  body {
      border: 1px solid #ccc;
  }
  #wrap{
      width:1050px;
      margin:auto;
  }
  h1, h2, h3{
      text-align: center;
      margin:0px;
      padding: 5px;
  }
  .formVal{
      font-weight: bold; display:inline;
      text-decoration: underline;
  }
  #admissionForm{
      font-size: 15px;
  }
  .left{
      float:left;
      min-width: 318px;
      max-width: 479px;
  }
  .right{
      float:right;
  }

  .text-center {
      text-align: center;
  }
  .text-right {
      text-align: right;
  }
  .el-row {
      padding: 10px 0px;
  }
  .el-col {
      padding: 5px 0px;
  }
  @media print{
    .el-col-md-6 {
        width: 25%;
    }
    .el-col-md-8 {
        width: 33.33333333%;
    }
    .el-col-md-12 {
        width: 50%;
    }
    .el-col-md-10 {
        width: 41.66666667%;
    }
    .el-col-md-14 {
        width: 58.33333333%;
    }
    .el-col-md-7 {
        width: 29.16666667%;
    }
  }
</style>

