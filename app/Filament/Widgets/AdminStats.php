<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\User;
use App\Models\Registration;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalEventos = Event::count();

        $totalParticipantes = User::whereHas('role', function ($q) {
            $q->where('name', 'Participante');
        })->count();

        $totalOrganizadores = User::whereHas('role', function ($q) {
            $q->where('name', 'Organizador');
        })->count();

        $totalVendas = Registration::where('status', 'confirmed')
            ->with('event')
            ->get()
            ->sum(function ($registration) {
                return $registration->event?->sale_price ?? 0;
            });

        return [
            Stat::make('Produtos', $totalEventos)
                ->description('Total de vinhos no catálogo')
                ->color('primary'),

            Stat::make('Clientes', $totalParticipantes)
                ->description('Clientes registados na loja')
                ->color('success'),

            Stat::make('Produtores', $totalOrganizadores)
                ->description('Utilizadores que gerem produtos')
                ->color('warning'),

            Stat::make('Vendas (€)', number_format($totalVendas, 2))
                ->description('Total de vendas confirmadas')
                ->color('danger'),
        ];
    }
}
