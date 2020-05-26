import gsbaddi from '@/api/settings/gsbaddi';
import gskheda from '@/api/settings/gskheda';
import gsbaroti from '@/api/settings/gsbaroti';
import localhost from '@/api/settings/localhost';
import vidyastra from '@/api/settings/vidyastra';
import demo from '@/api/settings/demo';
import ggsubathu from '@/api/settings/ggsubathu';
import kothideora from '@/api/settings/kothideora';
import kwarg from '@/api/settings/kwarg';
import moment from 'moment';

var mypath = ((window.location.origin).replace('http://', '').replace('https://', '')).split(':');
var myurl = (mypath[0]).split('.')[0]; // console.log(myurl);
var today = new Date(moment());

var hrs = today.getHours();
var atSession = hrs >= 12 ? 'Evening' : 'Morning';
// console.log(today+" hours: "+today.getHours());
var schools = {
  gsbaddi,
  gskheda,
  gsbaroti,
  localhost,
  vidyastra,
  demo,
  ggsubathu,
  kothideora,
  kwarg,
};

// console.log(schools[_schools[myurl]]['classes']);
var defaultSettings = {
  today: today,
  myurl: myurl,
  roles: ['admin', 'manager', 'editor', 'user', 'visitor'],
  nonAdminRoles: ['editor', 'user', 'visitor'],
  states: [
    'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 'Goa', 'Gujarat', 'Haryana',
    'Himachal Pradesh', 'Jharkhand', 'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur',
    'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana',
    'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal',
  ],
  atSession: atSession,
  listQuery: {
    page: 1,
    limit: 50,
    keyword: '',
    search: 'Class | subject | section',
    atSession: atSession,
    session: '',
    atDate: '',
    imon: '',
    allot: [],
    userId: 1,
    role: '',
  },
  sessions: [],
  searchBy: ['st_id', 'uid', 'fullName', 'father', 'class | section', 'class | subject | section', 'admsNumber'],

  schools,
  allotedOptions: ['Can Mark Attendance', 'Can Notify Attendance'],
};

export default defaultSettings;
