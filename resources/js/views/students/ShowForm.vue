<template>
  <div class="app-container">
    <div id="wrap">
      <h1>{{ schoolName }}</h1>
      <h2>{{ schoolAddress }}</h2>

      <h3>Admission Form {{ admissionForm.session }}</h3>
      <hr>
      <div id="admissionForm">

        <label>Name </label> : <div class="formVal">{{ admissionForm.fullName }} ({{ admissionForm.gender }})</div> &nbsp;
        <label>Aadhar/Uid</label> : <div class="formVal">{{ admissionForm.uid }}</div>&nbsp;
        <label>Date of Birth </label> :<div class="formVal">{{ admissionForm.dob }}</div>&nbsp;&nbsp;
        <label>Form No</label> : <div class="formVal">{{ admissionForm.st_id }}</div>&nbsp;&nbsp;
        <label>Admission No</label> : <div class="formVal">{{ admissionForm.admsNumber }}<hr></div>
        <div id="personal" class="left top-block">
          <label>Father's Name</label> : <div class="formVal">{{ admissionForm.father }}<hr></div>
          <label>Mother's Name</label> : <div class="formVal">{{ admissionForm.mother }}<hr></div>
          <label>CWSN </label> : <div class="formVal">{{ admissionForm.cwsn }}<hr></div>
          <label> Social Category</label> : <div class="formVal">{{ admissionForm.category }}({{ admissionForm.subCategory }})<hr></div>
          <label>Occupation </label> : <div class="formVal">{{ admissionForm.occupation }}<hr></div>
          <label>Annual Income </label> : <div class="formVal">{{ admissionForm.income }}<hr></div>
          <label>Religion </label> : <div class="formVal">{{ admissionForm.religion }}<hr></div>
        </div>
        <div id="subjects" class="top-block left">
          <h3>Subject Information</h3>
          <h3>
            <span v-if="admissionForm['class'][0]">
              Class: {{ admissionForm['class'][0] }}
            </span>&nbsp;&nbsp;
            <span v-if="admissionForm['class'][1]">
              Stream: {{ admissionForm['class'][1] }}
            </span>
          </h3><hr>
          Subjects opted:-
          <ul>
            <li v-for="sub in admissionForm.subject" :key="sub" :label="sub" :value="sub">{{ sub }}</li>
          </ul>
          <h2>{{ admissionForm.type }}</h2>
        </div>
        <div id="photo" class="right"><img v-if="admissionForm.image" ref="myimg" :src="admissionForm.image" class="avatar"></div>
        <br class="clear">
        <hr>
        <div class="left address">
          <strong>Permanent Address : </strong>{{ admissionForm['addressLine'][0] }}<br>
          {{ admissionForm['city'][0] }},&nbsp;
          {{ admissionForm['state'][0] }},&nbsp;
          {{ admissionForm['pinCode'][0] }},&nbsp;
          {{ admissionForm.country }}
        </div>
        <div class="right address">
          <strong>Corrospendance Address : </strong>{{ admissionForm['addressLine'][1] }}<br>
          {{ admissionForm['city'][1] }},&nbsp;
          {{ admissionForm['state'][1] }},&nbsp;
          {{ admissionForm['pinCode'][1] }},&nbsp;
          {{ admissionForm.country }}
        </div>
        <br class="clear">
        <hr>
        Parent's Mobile Number : <strong>{{ admissionForm.contact }}</strong>
        <hr>
        <h3>Bank Details</h3>
        Bank Name : <strong>{{ admissionForm.bankName }}</strong>&nbsp;
        Account Number : <strong>{{ admissionForm.bankAccount }}</strong>&nbsp;
        IFSC Code : <strong>{{ admissionForm.bankIfsc }}</strong>
        Branch : <strong>{{ admissionForm.bankBranch }}</strong>
        <hr>
        <h3>Previous Class(s) Result</h3>

      </div>
      <br class="clear">
      <div id="acaHistory" class="box">
        <el-table :data="admissionForm.prevClassResult" style="width: 100%">
          <el-table-column :xs="24" :sm="8" :md="3" prop="achYear" label="Year" />
          <el-table-column :xs="24" :sm="8" :md="3" prop="achClass" label="Class" />
          <el-table-column :xs="24" :sm="8" :md="3" prop="achRollNumber" label="Roll No." />
          <el-table-column :xs="24" :sm="8" :md="3" prop="achMarks" label="MxMarks" />
          <el-table-column :xs="24" :sm="8" :md="3" prop="achScored" label="Scored" />
          <el-table-column :xs="24" :sm="8" :md="3" prop="achBoard" label="Board" />
          <el-table-column :xs="24" :sm="8" :md="3" prop="achResult" label="Result" />
        </el-table>
      </div>
      <hr>
      <h3>Checklist of Documents</h3>
      <ul id="checklist">
        <li>Matric Certificate </li>
        <li>School Leaving Certificate</li>
        <li>Character Certificate</li>
        <li>Migration Certificate</li>
        <li>Aadhar Card</li>
        <li>Category Certificate </li>
      </ul>
      <br class="clear">
      <hr>
      Declaration:
      <ul>
        <li>I hereby declare that all the information given in this form is correct and true according to the best of my knowledge. If any information is found incorrect my admission may be cancelled </li>
        <li>I promise that I will follow all rules and regulations of the school and observe discipline. I will not cause any damage to school property in any form</li>
      </ul>
      <br class="clear"><br><br><br>
      <div class="left">Signature of the candidate</div>
      <div class="right">Signature of Guardian </div>
      <br class="clear">
      <hr>

      <h3>Admission Committee</h3>
      <br><br><br>
      <div class="member">1</div>
      <div class="member">2</div>
      <div class="member">3</div>

      <br><hr><br><br><br>
      <div class="left">Signature of the Convenor</div>
      <div class="right">Principal </div>

      <br class="clear">
      <div class="right"> Date : _________________ </div>
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
      },
      schoolName: settings['schools'][settings.myurl]['schoolName'],
      schoolAddress: settings['schools'][settings.myurl]['schoolAddress'],

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
      fetchStudent(id, thisSession, ad_id)
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
            this.admissionForm.prevClassResult = JSON.parse(element.prevClassResult) ? JSON.parse(element.prevClassResult) : [];

            // console.log(element);
          });
          setTimeout(() => {
            this.fullscreenLoading = false;
            this.$nextTick(() => {
              window.print();
              window.close();
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
  .dropdown-submenu {
      position: relative;
  }

  .dropdown-submenu .dropdown-menu {
      top: 0;
      left: 100%;
      margin-top: -1px;
  }
  /* Other CSS */

  #search{
      display:block;
      width:100%;
      border: 1px solid black;
      clear:both;
      padding-bottom: 5px;
  }
  .top-block{
      width:318px;
  }
  form label {
      float: left;
      width: 150px;
      margin-bottom: 5px;
      margin-top: 5px;
  }
  form#admissionForm  select, input[type="text"]{
      float:left;
      width:280px;
      margin-bottom: 2px;
      margin-top: 5px;
  }
  div.Address{
      width:450px;
      float:left;
  }
  .clear {
      display: block;
      clear: both;
      width: 100%;
  }
  #wrap{
      width:960px;
      margin:auto;
  }
  h1, h2, h3{
      text-align: center;
      Margin:0px;
  }
  h1{
      font-size:40px;
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
  .member{
      width:318px;
      float:left;
  }
  #stream, #radio_home{
      min-width:400px;
      float:left;
  }
  #personal label{
      width:120px;
      display:inline-block;
  }
  .el-table--medium th, .el-table--medium td {
    padding: 3px 0;
  }

  #subjects{
      padding: 0 20px;
      border:solid black 2px;
      margin:3px;
  }

  #photo{
      border:solid black 2px;

      text-align: center;
      padding:5px;
  }
  ul#checklist li{
      width:300px;
      float:left;
      height:20px;
  }
  .address{
      width:450px;
  }
  #FeeForm{
      width:500px;
      text-align: center;
      Margin:auto;
  }
  #navigation ul li,#navigation ul li a  {
      display:inline-block;
      text-decoration: none;
      color: black;
      font-weight: bold;
      padding:3px;
      text-align: center;
      background-color:blue;

  }
  #navigation ul li a:hover, #navigation ul li a:active   {
      background-color:green;
  }
  input.query{
      float:none;
  }
  input[type='text'].query{
      float:right;
      width:100px;
  }
  .fields{
      width:239px;
      float:left;
  }
  .avatar {
    width: 150px;
    height: auto;
    display: block;
  }
  @media print{
      input[type='button']{
          display:none
      }
      #navigation{
          display:none;
      }
      #QueryBuilder{
          display:none;
      }
  }
</style>

