export const formatDate = (date: string) => {
  if (!date) return '---';
  // A API retorna dd/mm/yyyy.
  return date;
};

export const statusClass = (status: string | undefined) => {
  const base = "border border-current transition-all";
  switch(status) {
    case 'aprovado': return `${base} bg-success-50 text-success-600 border-success-200`;
    case 'cancelado': return `${base} bg-secondary-50 text-secondary-600 border-secondary-200`;
    case 'solicitado': return `${base} bg-warning-50 text-warning-600 border-warning-200`;
    default: return 'bg-neutral-100 text-neutral-500 border-neutral-200';
  }
};
