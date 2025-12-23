export interface TravelRequest {
  id: number;
  destination: string;
  departure_date: string;
  return_date: string;
  status: 'solicitado' | 'aprovado' | 'cancelado';
  requester_name: string;
}
