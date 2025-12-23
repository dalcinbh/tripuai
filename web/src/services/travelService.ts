import api from './api';

interface CreateTravelData {
  requester_name: string;
  destination: string;
  departure_date: string;
  return_date: string;
}

export const travelService = {
  async getAll(params?: any) {
    const response = await api.get('/travel-requests', { params });
    return response.data;
  },
  async create(data: CreateTravelData) {
    const response = await api.post('/travel-requests', data);
    return response.data;
  },
  async approve(id: number) {
    const response = await api.patch(`/travel-requests/${id}/approve`);
    return response.data;
  },
  async cancel(id: number) {
    const response = await api.patch(`/travel-requests/${id}/cancel`);
    return response.data;
  },
  async sendBroadcast(data: { subject: string, body: string }) {
    const response = await api.post('/admin/broadcast', data);
    return response.data;
  }
};