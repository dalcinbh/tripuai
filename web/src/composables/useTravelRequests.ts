import { ref } from 'vue';
import { travelService } from '@/services/travelService';
import { toast } from 'vue3-toastify';

export interface TravelRequest {
  id: number;
  destination: string;
  departure_date: string;
  return_date: string;
  status: 'solicitado' | 'aprovado' | 'cancelado';
  requester_name: string;
}

export function useTravelRequests() {
  const requests = ref<TravelRequest[]>([]);
  const loading = ref(false);
  const currentFilters = ref({});
  const pagination = ref({ 
    current_page: 1, 
    last_page: 1, 
    from: 0, 
    to: 0, 
    total: 0 
  });

  const loadRequests = async (page = 1) => {
    try {
      loading.value = true;
      const response = await travelService.getAll({ ...currentFilters.value, page });
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
    currentFilters.value = newFilters;
    loadRequests(1);
  };

  const approve = async (id: number) => {
    try {
      await travelService.approve(id);
      toast.success('Viagem aprovada com sucesso!');
      await loadRequests(pagination.value.current_page);
    } catch (e) {
      toast.error('Erro ao aprovar viagem.');
    }
  };

  const cancel = async (id: number) => {
    try {
      await travelService.cancel(id);
      toast.success('Viagem cancelada.');
      await loadRequests(pagination.value.current_page);
    } catch (e) {
      toast.error('Erro ao cancelar viagem.');
    }
  };

  return {
    requests,
    loading,
    pagination,
    loadRequests,
    handleFilterChange,
    approve,
    cancel
  };
}
