<?php

namespace App\Http\Livewire;

use App\Cpanel\CpanelApi;
use App\View\Columns\HtmlColumn;
use App\View\Columns\ScreenshotColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\AppInstallation;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;

class CpanelInstallationsTable extends DataTableComponent
{
    protected bool $offlineIndicatorStatus = false;
    protected $model = AppInstallation::class;

    protected $listeners = ['refreshInstallations' => '$refresh'];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        // $this->setDebugEnabled();
        $this->setQueryStringDisabled();
        $this->setFiltersDisabled();
    }

    public function columns(): array
    {
        return [
         //   Column::make("Id", "id")->sortable(),
            ScreenshotColumn::make("Screenshot", "screenshot")
                ->location(function($row) {
                 return $row->getScreenshotUrl();
            }),

            HtmlColumn::make('Details')
                ->setOutputHtml(function($row) {
                    $html = '<div><a href="'.$row->url.'" target="_blank"><b>'.$row->url.'</b></a></div>';
                    $html .= '<div>'.$row->path.'</div>';
                    if ($row->is_symlink) {
                        $html .= '<div><span class="badge bg-success">Symlink</span></div>';
                    } else {
                        $html .= '<div><span class="badge bg-success">Standalone</span></div>';
                    }
                    if ($row->version > 0) {
                        $html .= '<div><span class="badge bg-success">v'.$row->version.'</span></div>';
                    }
                    return $html;
                }),

            Column::make("Owner", "user"),
            Column::make("Created At", "created_at"),

            HtmlColumn::make('Actions')
                ->setOutputHtml(function($row) {
                    $html = '
                    <a href="'.asset('microweber.live.php?router=installation/' . $row->id).'" class="btn btn-outline-dark btn-sm">Details</a>

                    ';
                    //<a href="'.$row->url.'" target="_blank" class="btn btn-outline-dark btn-sm">Go to website</a>
                    return $html;
                }),
        ];
    }

    public function builder() : Builder
    {
        $query = AppInstallation::query();

        $query->select(['*']);

        if ($this->hasSearch()) {
            $search = $this->getSearch();
            $search = trim(strtolower($search));
            $query->whereRaw('LOWER(`domain`) LIKE ? ',['%'.$search.'%']);
            $query->orWhereRaw('LOWER(`document_root`) LIKE ? ',['%'.$search.'%']);
            $query->orWhereRaw('LOWER(`home_dir`) LIKE ? ',['%'.$search.'%']);
        }

        $query->orderBy('created_at','asc');

        $cpanelApi = new CpanelApi();
        $query->where('user', $cpanelApi->getUsername());

        return $query;
    }

}
