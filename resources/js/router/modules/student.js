import Layout from '@/layout';

const manageStudentRoutes = {
  path: '/students',
  component: Layout,
  redirect: '/students/new',
  name: 'Manage Student',
  alwaysShow: true,
  meta: {
    title: 'manageStudent',
    icon: 'layout',
    permissions: ['view menu student'],

  },
  children: [
    /* {
      path: 'new-enquiry',
      name: 'Enquiry(New)',
      component: () => import('@/views/students/newenquiry'),
      meta: { title: 'New Enquiry', icon: 'form', permissions: ['manage user']},
    },*/
    {
      path: 'edit-enquiry/:id(\\d+)/enq',
      name: 'Promote',
      component: () => import('@/views/students/edit'),
      meta: { title: 'Promote to Admission', icon: 'form', permissions: ['manage user'] },
      hidden: true,
    },

    {
      path: 'new',
      name: 'New',
      component: () => import('@/views/students/new'),
      meta: { title: 'newStudent', icon: 'form', permissions: ['manage user'] },
    },
    {
      path: 'edit/:id(\\d+)/:ad_id(\\d+)',
      name: 'Edit',
      component: () => import('@/views/students/edit'),
      meta: { title: 'editStudent', icon: 'form', permissions: ['manage user'] },
      hidden: true,
    },
    {
      path: 'slcs/:id(\\d+)/:ad_id(\\d+)',
      name: 'SLC',
      component: () => import('@/views/students/slc'),
      meta: { title: 'slcStudent', icon: 'form', permissions: ['manage user'] },
      hidden: true,
    },
    {
      path: 'list',
      component: () => import('@/views/students/list'),
      name: 'List',
      meta: { title: 'Admissions', icon: 'icon', noCache: true },
    },
  ],
};

export default manageStudentRoutes;
