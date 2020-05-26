<template>
  <student-detail :is-edit="false" />
</template>

<script>
import StudentDetail from './components/StudentDetail';

export default {
  name: 'CreateForm',
  components: { StudentDetail },
};
</script>

