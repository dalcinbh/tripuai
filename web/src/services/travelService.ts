import api from './api';

export const travelService = {
  async getAll() {
    const response = await api.get('/travel-requests');
    return response.data;
  },
  async create(data: { destination: string; start_date: string; end_date: string }) {
    const response = await api.post('/travel-requests', data);
    return response.data;
  },
  async approve(id: number) {
    const response = await api.post(`/travel-requests/${id}/approve`);
    return response.data;
  },
  async cancel(id: number) {
    const response = await api.post(`/travel-requests/${id}/cancel`);
    return response.data;
  }
};