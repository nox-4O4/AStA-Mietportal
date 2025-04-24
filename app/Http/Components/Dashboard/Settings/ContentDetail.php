<?php

	namespace App\Http\Components\Dashboard\Settings;

	use App\Models\Content;
	use App\Util\Markdown;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Locked;
	use Livewire\Attributes\Title;
	use Livewire\Component;

	#[Title('Inhalt bearbeiten')]
	#[Layout('layouts.dashboard')]
	class ContentDetail extends Component {

		#[Locked]
		public Content $content;

		public string $contentValue;

		public function mount() {
			$this->contentValue = $this->content->content;
		}

		public function render() {
			return view('components.dashboard.settings.content-detail');
		}

		public function save() {
			$this->validate(['contentValue' => 'string|sometimes']);

			$this->content->content = $this->contentValue;
			$this->content->save();

			session()->flash('status.success', "Inhalt „{$this->content->name}“ aktualisiert.");
			$this->redirectRoute('dashboard.settings.contents.list', navigate: true);
		}

		public function getPreview(): string {
			return new Markdown($this->contentValue)->render();
		}
	}
