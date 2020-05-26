import request from '@/utils/request';

export function fetchList(query) {
  // console.log(query);
  return request({
    url: '/students',
    method: 'get',
    params: query,
  });
}

export function fetchStudent(id, thisSession, ad_id, fetch = false) {
  return request({
    url: '/students/' + id + '/' + ad_id,
    method: 'get',
    params: { session: thisSession, fetch: fetch },
  });
}
export function fetchPv(id) {
  return request({
    url: '/students/' + id + '/pageviews',
    method: 'get',
  });
}

export function createStudent(data) {
  // console.log(data);
  const config = {
    headers: { 'content-type': 'multipart/form-data' },
  };
  return request({
    url: '/student/create',
    method: 'post',
    data: data,
    config,
  });
}
export function createEnquiry(data) {
  // console.log(data);
  const config = {
    headers: { 'content-type': 'multipart/form-data' },
  };
  return request({
    url: '/student/enquiry',
    method: 'post',
    data: data,
    config,
  });
}
export function updateStudent(data) {
  return request({
    url: '/student/update',
    method: 'post',
    data,
  });
}

