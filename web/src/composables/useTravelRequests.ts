import { ref } from 'vue';
import { travelService } from '@/services/travelService';
import { toast } from 'vue3-toastify';
import type { TravelRequest } from '@/types/TravelRequest';

export function useTravelRequests() {
  const requests = ref<TravelRequest[]>([]);
  const loading = ref(false);
  const filters = ref({});
  const pagination = ref({ 
    current_page: 1, 
    last_page: 1, 
    from: 0, 
    to: 0, 
    total: 0 
  });

  const fetchRequests = async (page = 1) => {
    try {
      loading.value = true;
      const response = await travelService.getAll({ ...filters.value, page });
      requests.value = response.data; 
      pagination.value = {
        current_page: response.meta?.current_page || response.current_page,
        last_page: response.meta?.last_page || response.last_page,
        from: response.meta?.from || response.from,
        to: response.meta?.to || response.to,
        total: response.meta?.total || response.total
      };
    } catch (error) {
      toast.error('Erro ao carregar pedidos.');
    } finally {
      loading.value = false;
    }
  };

  const handleFilterChange = (newFilters: any) => {
    filters.value = newFilters;
    fetchRequests(1);
  };

  const approveRequest = async (id: number) => {
    try {
      await travelService.approve(id);
      toast.success('Viagem aprovada com sucesso!');
      await fetchRequests(pagination.value.current_page);
    } catch (e) {
      toast.error('Erro ao aprovar viagem.');
    }
  };

  const cancelRequest = async (id: number) => {
    try {
      await travelService.cancel(id);
      toast.success('Viagem cancelada.');
      await fetchRequests(pagination.value.current_page);
    } catch (e) {
      toast.error('Erro ao cancelar viagem.');
    }
  };

  return {
    requests,
    loading,
    filters,
    pagination,
    fetchRequests,
    handleFilterChange,
    approveRequest,
    cancelRequest
  };
}
