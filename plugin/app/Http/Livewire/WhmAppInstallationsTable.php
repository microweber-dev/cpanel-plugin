<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\AppInstallation;

class WhmAppInstallationsTable extends DataTableComponent
{
    protected bool $offlineIndicatorStatus = false;
    protected $model = AppInstallation::class;

    protected $listeners = ['refreshInstallations' => '$refresh'];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        // $this->setDebugEnabled();
        $this->setQueryStringDisabled();
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Domain", "domain"),
            Column::make("User", "user"),
            Column::make("Version", "version"),
            Column::make("Home dir", "home_dir"),
            Column::make("Symlink", "is_symlink"),
            Column::make("Standalone", "is_standalone"),
            Column::make("Version", "version"),
            Column::make("PHP Version", "php_version"),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
