<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelRequest extends Model
{
    const STATUS_SOLICITADO = 'solicitado';
    const STATUS_APROVADO = 'aprovado';
    const STATUS_CANCELADO = 'cancelado';

    protected $fillable = [
        'user_id',
        'requester_name',
        'destination',
        'departure_date',
        'return_date',
        'status',
    ];

    protected $casts = [
        'departure_date' => 'datetime',
        'return_date' => 'datetime',
    ];

    public function scopeForUser($query, User $user)
    {
        return $user->is_admin ? $query : $query->where('user_id', $user->id);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['status'] ?? null, function ($q, $status) {
            $q->where('status', $status);
        });

        $query->when($filters['destination'] ?? null, function ($q, $destination) {
            $q->where('destination', 'like', "%{$destination}%");
        });

        $query->when(isset($filters['start_date'], $filters['end_date']), function ($q) use ($filters) {
            $q->whereBetween('departure_date', [$filters['start_date'], $filters['end_date']]);
        });

        return $query;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APROVADO;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELADO;
    }

    public function markAsApproved()
    {
        $this->update(['status' => self::STATUS_APROVADO]);
    }

    public function markAsCancelled(): void
    {
        if ($this->isApproved()) {
            throw new \DomainException('Não é possível cancelar um pedido que já foi aprovado.');
        }

        if ($this->isCancelled()) {
            throw new \DomainException('Não é possível cancelar um pedido que já foi cancelado.');
        }

        $this->update(['status' => self::STATUS_CANCELADO]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
