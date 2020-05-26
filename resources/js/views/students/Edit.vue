<template>
  <student-detail :is-edit="true" />
</template>

<script>
import StudentDetail from './components/StudentDetail';

export default {
  name: 'EditForm',
  components: { StudentDetail },
};
</script>

