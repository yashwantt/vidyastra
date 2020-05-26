<template>
  <enquiry-detail :is-edit="false" />
</template>

<script>
import EnquiryDetail from './components/EnquiryDetail';

export default {
  name: 'CreateForm',
  components: { EnquiryDetail },
};
</script>

