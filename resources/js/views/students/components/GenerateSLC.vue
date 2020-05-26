<template>
  <div class="app-container">
    <el-form
      ref="admissionForm"
      label-position="top"
      :model="admissionForm"
      :rules="rules"
      @submit.prevent="submit"
    >
      <el-row>

        <el-tabs v-model="activeName" type="card">
          <el-tab-pane label="Fill SLC Information Properly" name="admissionInfo">
            <el-collapse v-model="activeNames">
              <el-collapse-item name="1">
                <template slot="title">
                  <h3>Admission Information</h3>
                </template>
                <el-row>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-form-item label="Date of Admission" prop="doa">
                      <el-date-picker
                        v-model="admissionForm.doa"
                        default-value=""
                        type="date"
                        format="dd-MM-yyyy"
                        value-format="yyyy-MM-dd"
                        @change="setSession"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-form-item label="For Session">
                      <el-input
                        v-model="admissionForm.session"
                        class="w193"
                        type="input"
                        :disabled="true"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-form-item label="Admission Type" prop="type">
                      <el-select v-model="admissionForm.type" default-first-option clearable>
                        <el-option label="Provisional" value="Provisional" />
                        <el-option label="Regular" value="Regular" />
                      </el-select>
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-form-item label="Admission Number" prop="number">
                      <el-input v-model="admissionForm.number" class="w193" type="input" />
                    </el-form-item>
                  </el-col>
                </el-row>
                <el-row>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-row>
                      <el-col :xs="24" :sm="12" :md="12">
                        <el-form-item label="Class" prop="class">
                          <el-cascader
                            v-model="admissionForm.class"
                            :options="classes"
                            filterable
                            default-first-option

                            @change="setSubjects"
                          />
                        </el-form-item>
                      </el-col>
                      <el-col :xs="24" :sm="12" :md="8">
                        <el-form-item v-if="admissionForm.section" label="Section" prop="section">
                          <el-select
                            v-model="admissionForm.section"

                            filterable
                            default-first-option
                          >
                            <el-option
                              v-for="section in sections"
                              :key="section"
                              :label="section"
                              :value="section"
                            />
                          </el-select>
                        </el-form-item>
                      </el-col>
                    </el-row>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-form-item label="Subjects" prop="subject">
                      <el-select
                        v-model="admissionForm.subject"

                        filterable
                        :value="subjects"
                        multiple
                        @change="changeSubjects"
                      >
                        <el-option
                          v-for="subject in subjects"
                          :key="subject"
                          :label="subject"
                          :value="subject"
                        />
                      </el-select>
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-form-item v-if="subjectSections" label="Subject/sections" prop="subjectSections">
                      <el-select
                        v-model="admissionForm.subjectSections"
                        filterable
                        default-first-option
                        clearable
                        multiple
                      >
                        <el-option
                          v-for="option in subjectSections"
                          :key="option"
                          :label="option"
                          :value="option"
                        />
                      </el-select>
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-form-item label="Child with Special Needs" prop="cwsn">
                      <el-select v-model="admissionForm.cwsn" clearable>
                        <el-option label="No" value="No" />
                        <el-option label="Yes" value="Yes" />
                      </el-select>
                    </el-form-item>
                  </el-col>
                </el-row>
              </el-collapse-item>
              <el-collapse-item name="2">
                <template slot="title">
                  <h3>Student Information</h3>
                </template>
                <el-row>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-form-item label="UID/Adhaar" prop="uid">
                      <el-input v-model="admissionForm.uid" class="w193" type="input" />
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-form-item label="Full Name" prop="fullName">
                      <el-input v-model="admissionForm.fullName" class="w193" type="input" />
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-form-item label="Gender/Sex" prop="gender">
                      <el-select v-model="admissionForm.gender">
                        <el-option label="Girl" value="Girl" />
                        <el-option label="Boy" value="Boy" />
                        <el-option label="Trans" value="Trans" />
                      </el-select>
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-form-item label="Date of Birth" prop="dob">
                      <el-date-picker
                        v-model="admissionForm.dob"
                        type="date"
                        format="dd-MM-yyyy"
                        value-format="yyyy-MM-dd"
                      />
                    </el-form-item>
                  </el-col>
                </el-row>

                <el-row>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-form-item label="Father Name" prop="father">
                      <el-input v-model="admissionForm.father" class="w193" type="input" />
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-form-item label="Occupation" prop="occupation">
                      <el-select
                        v-model="admissionForm.occupation"

                        filterable
                        default-first-option
                      >
                        <el-option label="Farmer" value="Farmer" />
                        <el-option label="Business" value="Business" />
                        <el-option label="Govt. Job" value="Govt. Job" />
                        <el-option label="Private Job" value="Private Job" />
                        <el-option label="Any Other" value="Any Other" />
                      </el-select>
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-form-item label="Father's Annual Income" prop="income">
                      <el-input v-model="admissionForm.income" class="w193" type="input" />
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-form-item label="Mother Name" prop="mother">
                      <el-input v-model="admissionForm.mother" class="w193" type="input" />
                    </el-form-item>
                  </el-col>
                </el-row>
                <el-row>
                  <el-col :xs="24" :sm="8" :md="6">
                    <el-form-item label="Contact Number" prop="contact">
                      <el-input v-model="admissionForm.contact" class="w193" type="input" />
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="8" :md="6">
                    <el-form-item label="Email Id">
                      <el-input v-model="admissionForm.email" class="w193" type="email" />
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-form-item label="Country" prop="country">
                      <el-input v-model="admissionForm.country" type="input" />
                    </el-form-item>
                  </el-col>

                </el-row>
                <el-row>
                  <el-col :xs="24" :sm="10" :md="10">
                    <el-form-item label="Permanent Address" prop="addressLine">
                      <el-input v-model="admissionForm['addressLine'][0]" type="input" />
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="6" :md="6">
                    <el-form-item label="State" prop="state">
                      <el-select
                        v-model="admissionForm['state'][0]"
                        filterable
                        default-first-option
                        clearable
                      >
                        <el-option
                          v-for="state in states"
                          :key="state"
                          :label="state"
                          :value="state"
                        />
                      </el-select>
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="4" :md="4">
                    <el-form-item label="City" prop="city">
                      <el-input v-model="admissionForm['city'][0]" type="input" />
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="4" :md="4">
                    <el-form-item label="Pin Code" prop="pinCode">
                      <el-input v-model="admissionForm['pinCode'][0]" type="input" />
                    </el-form-item>
                  </el-col>
                </el-row>
                <el-row>
                  <el-col :xs="24" :sm="10" :md="10">
                    <el-form-item label="Correspondance Address" prop="addressLine1">
                      <el-input v-model="admissionForm['addressLine'][1]" type="input" />
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="6" :md="6">
                    <el-form-item label="State" prop="state1">
                      <el-select
                        v-model="admissionForm['state'][1]"
                        filterable
                        default-first-option
                        clearable
                      >
                        <el-option
                          v-for="state in states"
                          :key="state"
                          :label="state"
                          :value="state"
                        />
                      </el-select>
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="4" :md="4">
                    <el-form-item label="City" prop="city1">
                      <el-input v-model="admissionForm['city'][1]" type="input" />
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="4" :md="4">
                    <el-form-item label="Pin Code" prop="pinCode1">
                      <el-input v-model="admissionForm['pinCode'][1]" type="input" />
                    </el-form-item>
                  </el-col>
                </el-row>
                <el-row>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-form-item label="Social Category" prop="category">
                      <el-select
                        v-model="admissionForm.category"

                        filterable
                        default-first-option
                      >
                        <el-option label="General" value="General" />
                        <el-option label="SC" value="Schedule Caste" />
                        <el-option label="ST" value="Schedule Tribe" />
                        <el-option label="OBC" value="Other Backward Classes" />
                      </el-select>
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-form-item label="Sub Category" prop="subCategory">
                      <el-select
                        v-model="admissionForm.subCategory"

                        filterable
                        default-first-option
                      >
                        <el-option label="NA" value="NA" />
                        <el-option label="BPL" value="BPL" />
                        <el-option label="IRDP" value="IRDP" />
                      </el-select>
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-form-item label="Religion" prop="religion">
                      <el-input v-model="admissionForm.religion" class="w193" type="input" />
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-form-item label="Locality" prop="locality">
                      <el-input v-model="admissionForm.locality" class="w193" type="input" />
                    </el-form-item>
                  </el-col>
                </el-row>
              </el-collapse-item>
              <el-collapse-item name="3">
                <template slot="title">
                  <h3>SLC Information</h3>
                </template>
                <el-row>
                  <el-col :xs="24" :sm="8" :md="8">
                    <el-row>
                      <el-col :xs="24" :sm="24" :md="24">
                        <el-form-item prop="slcFileNo">
                          <label>File No.: </label>
                          <el-input v-model="admissionForm.slcFileNo" type="input" style="width: 150px;" />
                        </el-form-item>
                      </el-col>
                      <el-col :xs="24" :sm="24" :md="24">
                        <el-form-item prop="issueDate">
                          <label>Issue Date: </label>
                          <el-date-picker
                            v-model="admissionForm.issueDate"
                            default-value=""
                            type="date"
                            format="dd-MM-yyyy"
                            value-format="yyyy-MM-dd"
                            style="width: 150px;"
                          />
                        </el-form-item>
                      </el-col>
                    </el-row>
                  </el-col>
                  <el-col :md="16">
                    <h3 style="text-align:left">He/she was examined in <u><span v-if="admissionForm['class'][0]">{{ admissionForm['class'][0] }}</span> <span v-if="admissionForm['class'][1]">{{ admissionForm['class'][1] }}</span></u> and </h3>
                    <br>
                    <el-radio-group v-model="admissionForm.slcRemarks" size="mini">
                      <ul>
                        <li><el-radio label="Was allowed/promised promotion to upper class">Was Allowed/Promised Promotion to Upper Class OR</el-radio></li>
                        <li><el-radio label="Passed the examination in highest class available in school">Passed the Examination in Highest Class Available in School OR</el-radio></li>
                        <li><el-radio label="Left the school mid-session to join a different school" />Left the School Mid-Session to join a different School, OR</el-radio></li>
                        <li><el-radio label="Failed in examination">Failed in Subject/Examination</el-radio></li>
                      </ul>
                    </el-radio-group>
                  </el-col>
                </el-row>
                <el-row>
                  <el-col :xs="24" :sm="24" :md="24">
                    <h3 style="text-align:left">The following particulars are certified to be correct according to the registers of this school and the certificate(s) produced from previous school(s) attended during school year:</h3>
                  </el-col>
                </el-row>
                <el-row>
                  <el-col :xs="24" :sm="24" :md="4">
                    <el-form-item label="School" prop="slcSchool">
                      <label>&nbsp;</label>
                      <el-input v-model="admissionForm.slcSchool" type="input" />
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="3">
                    <el-form-item label="Admission" prop="slcDOA">
                      <label>DATE</label>
                      <el-date-picker
                        v-model="admissionForm.slcDOA"
                        default-value=""
                        type="date"
                        format="dd-MM-yyyy"
                        value-format="yyyy-MM-dd"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="3">
                    <el-form-item label="Withdrawal" prop="slcDOW">
                      <label>DATE</label>
                      <el-date-picker
                        v-model="admissionForm.slcDOW"
                        default-value=""
                        type="date"
                        format="dd-MM-yyyy"
                        value-format="yyyy-MM-dd"
                      />
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="6">
                    <el-row>
                      <el-col :xs="24" :sm="12" :md="12">
                        <el-form-item label="Attendance" prop="slcPeriodFrom">
                          <label>From</label>
                          <el-date-picker
                            v-model="admissionForm.slcPeriodFrom"
                            default-value=""
                            type="date"
                            format="dd-MM-yyyy"
                            value-format="yyyy-MM-dd"
                          />
                        </el-form-item>
                      </el-col>
                      <el-col :xs="24" :sm="12" :md="12">
                        <el-form-item label="Period" prop="slcPeriodTo">
                          <label>To</label>
                          <el-date-picker
                            v-model="admissionForm.slcPeriodTo"
                            default-value=""
                            type="date"
                            format="dd-MM-yyyy"
                            value-format="yyyy-MM-dd"
                            @change="totalAttn"
                          />
                        </el-form-item>
                      </el-col>
                    </el-row>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="2">
                    <el-form-item label="Possible" prop="slcAtTotal">
                      <label>Attendance</label>
                      <el-input v-model="admissionForm.slcAtTotal" type="number" @focus="totalAttn" />
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="2">
                    <el-form-item label="Present" prop="slcAttended">
                      <label>&nbsp;</label>
                      <el-input v-model="admissionForm.slcAttended" type="number" />
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="12" :md="2">
                    <el-form-item label="Leave" prop="slcLeave">
                      <label>Taken</label>
                      <el-input v-model="admissionForm.slcLeave" type="number" />
                    </el-form-item>
                  </el-col>
                  <el-col :xs="24" :sm="24" :md="2">
                    <el-form-item label="Add">
                      <label>&nbsp;</label>
                      <el-button type="primary" icon="el-icon-plus" @click="addSLC" />
                    </el-form-item>
                  </el-col>
                </el-row>
                <el-table :data="admissionForm.slcData" stripe style="width: 100%">
                  <el-table-column width="175" prop="slcSchool" label="School" />
                  <el-table-column prop="slcDOA" label="D.O.A">
                    <template slot-scope="scope">
                      {{ scope.row.slcDOA.split("-")[2] }}-{{ scope.row.slcDOA.split("-")[1] }}-{{ scope.row.slcDOA.split("-")[0] }}
                    </template>
                  </el-table-column>
                  <el-table-column prop="slcDOW" label="D.O.W">
                    <template slot-scope="scope">
                      {{ scope.row.slcDOW.split("-")[2] }}-{{ scope.row.slcDOW.split("-")[1] }}-{{ scope.row.slcDOW.split("-")[0] }}
                    </template>
                  </el-table-column>
                  <el-table-column prop="slcPeriodFrom" label="Period From">
                    <template slot-scope="scope">
                      {{ scope.row.slcPeriodFrom.split("-")[2] }}-{{ scope.row.slcPeriodFrom.split("-")[1] }}-{{ scope.row.slcPeriodFrom.split("-")[0] }}
                    </template>
                  </el-table-column>
                  <el-table-column prop="slcPeriodTo" label="Period To">
                    <template slot-scope="scope">
                      {{ scope.row.slcPeriodTo.split("-")[2] }}-{{ scope.row.slcPeriodTo.split("-")[1] }}-{{ scope.row.slcPeriodTo.split("-")[0] }}
                    </template>
                  </el-table-column>
                  <el-table-column width="85" prop="slcAtTotal" label="Total" />
                  <el-table-column width="85" prop="slcAttended" label="Present" />
                  <el-table-column width="85" prop="slcLeave" label="Leave" />
                  <el-table-column width="85" label="DEL">
                    <template slot-scope="scope">
                      <el-button
                        icon="el-icon-delete"
                        type="danger"
                        @click="delSLC(scope.$index, scope.row)"
                      />
                    </template>
                  </el-table-column>
                </el-table>

              </el-collapse-item>
            </el-collapse>
          </el-tab-pane>
        </el-tabs>

      </el-row>
      <el-row class="text-center"><br>

        <el-button type="success" @click="submitForm('admissionForm')">Save</el-button>&nbsp;
        <el-button @click="resetForm('admissionForm')">Reset</el-button>

      </el-row>
    </el-form>
  </div>
</template>

<script>
import settings from '@/api/settings.js';
import { createStudent } from '@/api/student';
import { fetchStudent } from '@/api/student';

var today = settings.today;

export default {
  props: {
    isEdit: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      rules: {
        doa: [{ required: true, message: '*Required', trigger: 'change' }],
        type: [{ required: true, message: '*Required', trigger: 'change' }],
        class: [{ required: true, message: '*Required', trigger: 'change' }],
        subject: [{ required: true, message: '*Required', trigger: 'change' }],

        cwsn: [{ required: true, message: '*Required', trigger: 'change' }],

        fullName: [{ required: true, message: '*Required', trigger: 'change' }],
        uid: [{ required: true, message: '*Required', trigger: 'change' }],
        gender: [{ required: true, message: '*Required', trigger: 'change' }],
        dob: [{ required: true, message: '*Required', trigger: 'change' }],
        contact: [{ required: true, message: '*Required', trigger: 'change' }],
        // father: [{ required: true, message: '*Required', trigger: 'change' }],
        occupation: [
          { required: true, message: '*Required', trigger: 'change' },
        ],

        // mother: [{ required: true, message: '*Required', trigger: 'change' }],
        country: [{ required: true, message: '*Required', trigger: 'change' }],
        state: [{ required: true, message: '*Required', trigger: 'change' }],
        // city: [{ required: true, message: '*Required', trigger: 'change' }],
      },
      admissionForm: {
        userId: this.$store.getters.userId,
        unlinkImgs: [],
        image: '',
        images: [],
        imgs: '',
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
        slcAttended: '', slcLeave: '', slcData: [], slcRemarks: 'None', issueDate: '', slcFileNo: '', admsStatus: 'active',
      },
      states: settings.states,
      classes: [],
      prevClassSubject: '',
      sections: settings['schools'][settings.myurl]['sections']['total'],
      all: settings['schools'][settings.myurl]['all'],
      science: settings['schools'][settings.myurl]['science'],
      commerce: settings['schools'][settings.myurl]['commerce'],
      humanities: settings['schools'][settings.myurl]['humanities'],
      optionSubjects: settings['schools'][settings.myurl]['optionSubjects'],
      subjectSections: [],
      subjects: [],
      activeName: 'admissionInfo',
      activeNames: ['1', '2', '3'],
      status: 'published',
      addCount: 0, addCounts: 0,
    };
  },
  tempRoute: {},
  created(){
    this.classes = settings['schools'][settings.myurl]['classes'];

    this.admissionForm.slcDOW = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    this.admissionForm.issueDate = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    this.admissionForm.doa = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    this.setSession();
    if (this.isEdit) {
      const id = this.$route.params && this.$route.params.id;
      const ad_id = this.$route.params && this.$route.params.ad_id;
      this.fetchData(id, this.admissionForm.session, ad_id);
    }

    this.tempRoute = Object.assign({}, this.$route);
  },
  methods: {
    totalAttn(){
      const date1 = new Date(this.admissionForm.slcPeriodFrom);
      const date2 = new Date(this.admissionForm.slcPeriodTo);
      const diffTime = Math.abs(date2 - date1);
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      this.admissionForm.slcAtTotal = diffDays;
      console.log(diffDays);
    },
    addSLC: function(){
      const slcRow = {
        slcSchool: this.admissionForm.slcSchool,
        slcDOA: this.admissionForm.slcDOA,
        slcDOW: this.admissionForm.slcDOW,
        slcPeriodFrom: this.admissionForm.slcPeriodFrom,
        slcPeriodTo: this.admissionForm.slcPeriodTo,
        slcAtTotal: this.admissionForm.slcAtTotal,
        slcAttended: this.admissionForm.slcAttended,
        slcLeave: this.admissionForm.slcLeave,
      };
      // console.log(slcRow);
      for (var prop in slcRow) {
        if (prop == 'slcLeave') {
          continue;
        }
        if (slcRow[prop] == '') {
          this.$notify({
            title: 'Warning',
            message: 'All SLC fields are mandatory..!',
            type: 'warning',
            duration: 2000,
          });
          return;
        }
      }
      // console.log(slcRow);
      this.admissionForm.slcData.push(slcRow);
      ++this.addCounts;
    },
    delSLC: function(index, row) {
      this.admissionForm.slcSchool = row.slcSchool;
      this.admissionForm.slcDOA = row.slcDOA;
      this.admissionForm.slcDOW = row.slcDOW;
      this.admissionForm.slcPeriodFrom = row.slcPeriodFrom;
      this.admissionForm.slcPeriodTo = row.slcPeriodTo;
      this.admissionForm.slcAtTotal = row.slcAtTotal;
      this.admissionForm.slcAttended = row.slcAttended;
      this.admissionForm.slcLeave = row.slcLeave;

      this.admissionForm.slcData.splice(index, 1);
      if (this.addCounts > 0) {
        --this.addCounts;
      }
    },
    unlinkImg(el){
      this.admissionForm.unlinkImgs.push(el);
      this.admissionForm.images.splice(this.admissionForm.images.indexOf(el), 1);
      // console.log(this.admissionForm.unlinkImgs);
    },
    compress(source_img_obj, quality, maxWidth, output_format){
      var mime_type = 'image/jpeg';
      if (typeof output_format !== 'undefined' && output_format == 'png'){
        mime_type = 'image/png';
      }

      maxWidth = maxWidth || 1000;
      var natW = source_img_obj.naturalWidth;
      var natH = source_img_obj.naturalHeight;
      var ratio = natH / natW;
      if (natW > maxWidth) {
        natW = maxWidth;
        natH = ratio * maxWidth;
      }

      var cvs = document.createElement('canvas');
      cvs.width = natW;
      cvs.height = natH;

      var ctx = cvs.getContext('2d').drawImage(source_img_obj, 0, 0, natW, natH);
      var newImageData = cvs.toDataURL(mime_type, quality / 100);
      var result_image_obj = new Image();
      result_image_obj.src = newImageData;
      return result_image_obj;
    },
    onImageChange(e) {
      const files = e.target.files || e.dataTransfer.files;

      if (!files.length) {
        return;
      }
      this.createImage(files[0], 0);
    },
    onImagesChange(e) {
      const files = e.target.files || e.dataTransfer.files;

      if (!files.length) {
        return;
      }
      var v = this; var ij = 0;
      const intr = setInterval(function(){
        v.createImage(files[ij++], 1);
        if (ij >= files.length) {
          clearInterval(intr);
        }
      }, 3000);
    },
    createImage(file, n) {
      const isJPG = file.type === 'image/jpeg';
      const isLt100 = file.size / 1024 < 100;

      const reader = new FileReader();
      const vm = this;
      reader.onload = (e) => {
        if (n){
          vm.admissionForm.imgs = e.target.result;
          setTimeout(function(){
            // console.log(vm.compress(vm.$refs.myimgs, 75, 550, "jpg").src);
            vm.admissionForm.images.push(vm.compress(vm.$refs.myimgs, 75, 550, 'jpg').src);
            vm.admissionForm.imgs = '';
          }, 1000);
        } else {
          vm.admissionForm.image = e.target.result;
          setTimeout(function(){
            vm.admissionForm.image = vm.compress(vm.$refs.myimg, 75, 150, 'jpg').src;
          }, 1000);
        }
      };
      reader.readAsDataURL(file);
    },

    fetchData(id, thisSession, ad_id) {
      fetchStudent(id, thisSession, ad_id, true)
        .then(response => {
          console.log(response.data);
          response.data[0].forEach(element => {
            this.admissionForm.ad_id = element.ad_id;

            this.admissionForm.doa = element.doa;
            this.admissionForm.slcDOA = element.doa;
            this.admissionForm.session = element.session;
            this.admissionForm.type = element.type;
            this.admissionForm.number = element.number;
            this.admissionForm.class = JSON.parse(element.class) ? JSON.parse(element.class) : [];
            this.setSubjects();
            this.admissionForm.section = element.section;
            this.admissionForm.cwsn = element.cwsn;
            this.admissionForm.subject = JSON.parse(element.subject) ? JSON.parse(element.subject) : [];
            this.admissionForm.subjectSections = JSON.parse(element.subjectSections) ? JSON.parse(element.subjectSections) : [];

            this.admissionForm.st_id = element.st_id;
            this.admissionForm.uid = element.uid;
            this.admissionForm.dob = element.dob;
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
            this.admissionForm.images = (JSON.parse(element.attachments)) ? JSON.parse(element.attachments) : [];
            this.admissionForm.ac_id = element.ac_id;
            this.admissionForm.prevClassResult = JSON.parse(element.prevClassResult) ? JSON.parse(element.prevClassResult) : [];
            this.admissionForm.slcData = JSON.parse(element.slcData) ? JSON.parse(element.slcData) : [];
            this.admissionForm.slcRemarks = element.slcRemarks ? element.slcRemarks : 'None';
            this.admissionForm.issueDate = element.issueDate ? element.issueDate : today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();

            this.admissionForm.slcFileNo = element.slcFileNo;
            this.admissionForm.admsStatus = element.admsStatus;
            // console.log(element);
          });

          this.admissionForm.slcPeriodFrom = response.data[1][0].atDate;
          this.admissionForm.slcPeriodTo = response.data[1][response.data[1].length - 1].atDate;
          // console.log(response.data[1][0]);
          let atTotal = 0; let atPresent = 0; let atLeave = 0;
          response.data[1].forEach(element => {
            atTotal++;
            // console.log(JSON.parse(element.attendance));
            const attend = JSON.parse(element.attendance);
            // console.log(attend[0].SubjectWise[0]);
            if (attend[0].SubjectWise.length){
              attend[0].SubjectWise.forEach(elemen => {
                // console.log(elemen);
                const subject = elemen.split(' | ');
                // console.log(subject);
                if (subject[1] == 'English' && subject[3] == 'P'){
                  atPresent++;
                } else if (subject[1] == 'English' && subject[3] == 'L'){
                  atLeave++;
                }
              });
            } else if (attend[0].Morning == 'P' || attend[0].Evening == 'P'){
              atPresent++;
            } else if (attend[0].Morning == 'L' || attend[0].Evening == 'L'){
              atLeave++;
            }
          });
          this.admissionForm.slcAtTotal = atTotal;
          this.admissionForm.slcAttended = atPresent;
          this.admissionForm.slcLeave = atLeave;
        })
        .catch(err => {
          console.log(err);
        });
    },
    addAcademics: function() {
      const academicRow = {
        achYear: this.admissionForm.achYear,
        achClass: this.admissionForm.achClass[0],
        achRollNumber: this.admissionForm.achRollNumber,
        achMarks: this.admissionForm.achMarks,
        achScored: this.admissionForm.achScored,
        achBoard: this.admissionForm.achBoard,
        achResult: this.admissionForm.achResult,
      };

      for (var prop in academicRow) {
        if (academicRow[prop] == '') {
          this.$notify({
            title: 'Warning',
            message: 'All academic fields are mandatory..!',
            type: 'warning',
            duration: 2000,
          });
          return;
        }
      }

      this.admissionForm.prevClassResult.push(academicRow);
      ++this.addCount;
    },
    delAcademics: function(index, row) {
      this.admissionForm.achYear = row.achYear;
      this.admissionForm.achClass = row.achClass;
      this.admissionForm.achRollNumber = row.achRollNumber;
      this.admissionForm.achMarks = row.achMarks;
      this.admissionForm.achScored = row.achScored;
      this.admissionForm.achBoard = row.achBoard;
      this.admissionForm.achResult = row.achResult;

      this.admissionForm.prevClassResult.splice(index, 1);
      if (this.addCount > 0) {
        --this.addCount;
      }
    },
    submitForm: function(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          // console.log(this.admissionForm);

          createStudent(this.admissionForm).then(response => {
            // console.log(response.data);
            this.$notify({
              title: 'Success',
              message: response.data['msg'],
              type: 'success',
              duration: 2000,
            });
            // console.log(response.data['st_id']);
            // this.$router.push("/students/form/"+response.data['st_id']);
            this.$confirm('Do you print SLC..?', 'Warning', {
              confirmButtonText: 'Yes',
              cancelButtonText: 'No',
              type: 'warning',
            }).then(() => {
              const route = this.$router.resolve({ path: '/students/slc/' + response.data['st_id'] + '/' + this.$route.params.ad_id });
              // let route = this.$router.resolve('/link/to/page'); // This also works.
              window.open(route.href, '_blank');
            });
          });
        } else {
          console.log('Error submit!!');
          this.$notify({
            title: 'Error',
            message: 'Error in inserting, check student record required..!',
            type: 'error',
            duration: 2000,
          });
          return false;
        }
      });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    },
    setSubjects(n = 0) {
      if (n && this.prevClassSubject == this.admissionForm.class[1]){
        return false;
      }
      this.subjects = [];
      if (this.admissionForm.class[1] == 'Science') {
        this.admissionForm.subject = this.science.compulsary;
        this.subjects = this.science.choices;
      } else if (this.admissionForm.class[1] == 'Commerce') {
        this.admissionForm.subject = this.commerce.compulsary;
      } else if (this.admissionForm.class[1] == 'Humanities') {
        this.admissionForm.subject = this.humanities.compulsary;
        this.subjects = this.humanities.choices;
      } else {
        this.admissionForm.subject = this.all.compulsary;
        this.subjects = this.all.choices;
      }
      this.optionSubjects.forEach(element => {
        if (!this.subjects.includes(element)) {
          this.subjects.push(element);
        }
      });
      const vm = this;
      setTimeout(function(){
        vm.changeSubjects();
      }, 1000);
    },
    changeSubjects(){
      this.subjectSections = [];
      // console.log(this.admissionForm.subject);
      if (parseInt(this.admissionForm.class[0]) > settings['schools'][settings.myurl]['sections']['lg']){
        this.admissionForm.subject.forEach(element => {
          const gt = settings['schools'][settings.myurl]['sections']['gt'];
          gt.forEach(elemen => {
            if (elemen != 'NA') {
              this.subjectSections.push(element + ' | ' + elemen);
            }
          });
        });
        this.admissionForm.section = '';
      } else {
        this.subjectSections = '';
        this.admissionForm.section = 'NA';
      }
    },
    setSession: function() {
      var admDate = this.admissionForm.doa.split('-');
      if (parseInt(admDate[1]) >= 2){
        this.admissionForm.session = admDate[0] + '-' + (parseInt(admDate[0]) + 1);
      } else {
        this.admissionForm.session = parseInt(admDate[0]) - 1 + '-' + (parseInt(admDate[0]));
      }
    },
    onSubmit: function() {
      this.$message('submit!');
    },
    onCancel: function() {
      this.$message({
        message: 'cancel!',
        type: 'warning',
      });
    },
  },
};
</script>

<style scoped>
.w220 {
  width: 220px;
}
.w193 {
  width: 90%;
}
.line {
  text-align: center;
}
.avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
    border: 1px dotted;
  }
  .avatar {
    width: 178px;
    height: 178px;
    display: block;
  }
  .image-upload > input{
      display: none;
  }

  .image-upload img, .image-upload i {
      cursor: pointer;
  }
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
      width: 100px;
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
  td{
      border:solid black 1px;
  }
  td span{
      font-size: 8px;
  }
  #subjects{
      padding: 0 20px;
      border:solid black 2px;
      margin:3px;
  }

  #photo{
      border:solid black 2px;
      height: 140px;
      width:200px;
      text-align: center;
      padding-top:100px;
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
  .el-select--medium{
    width: 100%;
  }
  .anch {
    background:#409EFF;
    color: #fffff4;
    padding: 5px;
    position:absolute;
    top:0px; right: 0px;
    font-weight: bold;
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

