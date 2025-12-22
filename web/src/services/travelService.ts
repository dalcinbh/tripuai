import api from './api';

export const travelService = {
  // Lista todos os pedidos (Admin vê tudo, comum vê os seus)
  async getAll() {
    const response = await api.get('/travel-requests');
    return response.data;
  },

  // Aprovar um pedido
  async approve(id: number) {
    const response = await api.post(`/travel-requests/${id}/approve`);
    return response.data;
  },

  // Cancelar um pedido
  async cancel(id: number) {
    const response = await api.post(`/travel-requests/${id}/cancel`);
    return response.data;
  }
};