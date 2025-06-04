<?php
namespace App\Http\Livewire;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Mstock;
use App\Models\Branch;
use Illuminate\Support\Facades\Cache;
class ImportProgress extends Component
{
      public $progress = 0;
    public $done = false;
    public function mount()
    {
        $this->updateProgress();
    }
    public function updateProgress()
    {
        $this->progress = Cache::get('import_progress', 0);
        $this->done = Cache::get('import_progress_done', false);
    }
    public function render()
    {
        return view('livewire.import-progress');
    }
    public function polling()
    {
        $this->updateProgress();
    }
}