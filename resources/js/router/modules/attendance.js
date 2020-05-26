import Layout from '@/layout';

const manageAttendanceRoutes = {
  path: '/attendance',
  component: Layout,
  redirect: '/attendance/mark',
  name: 'Manage Attendance',
  alwaysShow: true,
  meta: {
    title: 'manageAttendance',
    icon: 'layout',
    permissions: ['view menu attendance'],

  },
  children: [
    {
      path: 'mark',
      component: () => import('@/views/attendance/mark'),
      name: 'Mark',
      meta: { title: 'markAttendance', icon: 'icon' },
    },
    {
      path: 'list',
      component: () => import('@/views/attendance/list'),
      name: 'Notify',
      meta: { title: 'notifyAttendance', icon: 'icon' },
    },
  ],
};

export default manageAttendanceRoutes;
