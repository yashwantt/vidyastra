const school = {
  schoolName: 'Govt Senior Secondary School',
  schoolAddress: 'Khera-Nanowal, Distt. Solan',
  udiseCode: '02090703001',
  depttSchoolCode: '',
  classes: [
    { value: '6', label: '6' },
    { value: '7', label: '7' },
    { value: '8', label: '8' },
    { value: '9', label: '9' },
    { value: '10', label: '10' },
    { value: '11', label: '11',
      children: [
        { value: 'Science', label: 'Science' },
        { value: 'Commerce', label: 'Commerce' },
        { value: 'Humanities', label: 'Humanities' },
      ],
    },
    { value: '12', label: '12',
      children: [
        { value: 'Science', label: 'Science' },
        { value: 'Commerce', label: 'Commerce' },
        { value: 'Humanities', label: 'Humanities' },
      ],
    },
  ],
  sections: {
    total: ['NA', 'A', 'B', 'C', 'D'],
    lt: ['NA', 'A', 'B', 'C', 'D'],
    gt: ['NA', 'A', 'B', 'C'],
    lg: 10,
  },
  subjects: ['All', 'English', 'Physics', 'Chemistry', 'Math', 'Biology',
    'Accountancy', 'Business Studies', 'Economics',
    'Hindi', 'History', 'Political Science',
    'Physical Education', 'Computer Science', 'Agriculture', 'Tourism',
  ],
  all: {
    compulsary: ['English', 'Hindi', 'Science', 'Math', 'SST', 'Sanskrit'],
    choices: [],
  },
  science: {
    compulsary: ['English', 'Physics', 'Chemistry'],
    choices: ['Math', 'Biology'],
  },
  commerce: {
    compulsary: ['Accountancy', 'Business Studies', 'Economics', 'English'],
    choices: [],
  },
  humanities: {
    compulsary: ['English'],
    choices: ['Math', 'Economics', 'Hindi', 'History', 'Political Science'],
  },
  optionSubjects: [
    'Art', 'Physical Education', 'Computer Science', 'Agriculture', 'Tourism',
  ],
  smsApiKey: '35CE943FB9994B',
  smsSenderId: 'GGSSBT',
};
export default school;
