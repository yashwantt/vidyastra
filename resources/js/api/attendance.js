import request from '@/utils/request';
import axios from 'axios';

export function fetchList(query) {
  return request({
    url: '/attendance',
    method: 'get',
    params: query,
  });
}

export function fetchUser(id) {
  // console.log("Getting record for: "+id);
  return request({
    url: '/udata/' + id,
    method: 'get',
  });
}

export function atMarked(data, n, smapkey, smsnid, schlnm) {
  // console.log(data);
  return request({
    url: '/attendance/marked/' + n + '/' + smapkey + '/' + smsnid + '/' + schlnm,
    method: 'post',
    data,
  });
}
