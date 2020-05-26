<template>
  <enquiry-detail :is-edit="true" />
</template>

<script>
import EnquiryDetail from './components/EnquiryDetail';

export default {
  name: 'EditForm',
  components: { EnquiryDetail },
};
</script>

