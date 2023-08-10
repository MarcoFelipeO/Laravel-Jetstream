<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Ticket;

//IMPORTAMOS ESTO PARA LOS BOTONES DE EDITAR Y ELIMINAR
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
//IMPORTAMOS ESTO PARA LOS BOTONES DE EDITAR Y ELIMINAR


//IMPORTAMOS EL FORMATO EXCEL
use App\Exports\TicketsExport;
use Maatwebsite\Excel\Facades\Excel;
//IMPORTAMOS EL FORMATO EXCEL



class TicketsTable extends DataTableComponent
{
    protected $model = Ticket::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }


        //TIEMPO DE ESPERA ENTRE CADA INSERCION QUE HAGA BUSQUEDAS EN LA BDD
        public ?int $searchFilterDebounce = 600;
        //TIEMPO DE ESPERA ENTRE CADA INSERCION QUE HAGA BUSQUEDAS EN LA BDD





        //EXPORTAR A EXCEL
        public function bulkActions(): array
        {
            return [
                'export' => 'Exportar',
                'deleteSelected' => 'Eliminar',
            ];
        }
                //EXPORTAR A EXCEL




    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),


            Column::make("Titulo", "title")
            ->sortable()    
            ->searchable(),

            Column::make("Descripción", "description")
            ->sortable()
            ->searchable(),


            Column::make("Usuarios", "user.name")
             ->sortable(function($builder, $direction){
                return $builder->orderBy('users.name', $direction);
             })
             ->searchable(),
            


            Column::make("Fecha Creación", "created_at")
                ->sortable()
                ->format(function ($value) {
                    return $value->format('d/m/Y h:i');
                }),

            Column::make("Fecha Actualización", "updated_at")
                ->sortable()
                ->format(function ($value) {
                    return $value->format('d/m/Y h:i');
                }),

            Column::make("Acciones", "id")
                ->format(function ($value) {
                    return view('tickets.datatables.actions', ['ticket' => Ticket::find($value)]);
                }, false),


        ];
    }


    public function filters(): array
    {
        return [

            DateFilter::make('Edit From')
                ->config([
                    'min' => '2023-01-01',
                    'max' => '2024-01-01',
                ])
                ->filter(function(Builder $builder, string $value) {
                    $builder->where('tickets.updated_at', '>=', $value);
                }),
            DateFilter::make('Edit To')
                ->filter(function(Builder $builder, string $value) {
                    $builder->where('tickets.updated_at', '<=', $value);
                }),
        ];
    }
 

}
