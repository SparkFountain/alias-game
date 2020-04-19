export interface Response<T> {
  status: 'success' | 'error' | 'fail';
  data?: T;
}
