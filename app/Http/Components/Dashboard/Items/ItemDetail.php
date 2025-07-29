<?php

	namespace App\Http\Components\Dashboard\Items;

	use App\Models\Image;
	use App\Models\Item;
	use App\Models\ItemGroup;
	use App\Traits\TrimWhitespaces;
	use Illuminate\Contracts\View\View;
	use Illuminate\Support\Arr;
	use Illuminate\Support\Collection;
	use Illuminate\Support\Facades\Validator;
	use Illuminate\Validation\Rule;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Locked;
	use Livewire\Component;
	use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
	use Livewire\WithFileUploads;

	#[Layout('layouts.dashboard')]
	class ItemDetail extends Component {
		use TrimWhitespaces, WithFileUploads;

		const int IMAGE_UPLOAD_SIZE_LIMIT_MB = 2;

		#[Locked]
		public Item $item;

		public string $name;
		public string $description;
		public bool   $available;
		public bool   $visible;
		public bool   $keepStock;
		public int    $stock;
		public float  $price;
		public int    $deposit;
		public string $itemGroup;

		/**
		 * @var array<TemporaryUploadedFile>
		 */
		public array $images = [];

		public function mount(Item $item): void {
			$this->item        = $item;
			$this->name        = $item->raw_name;
			$this->description = $item->description;
			$this->available   = $item->available;
			$this->visible     = $item->visible;
			$this->keepStock   = $item->amount > 0;
			$this->stock       = $item->amount;
			$this->price       = $item->price;
			$this->deposit     = $item->deposit;
			$this->itemGroup   = $item->itemGroup?->id ?? '';
		}

		public function maxSize(): int {
			return self::IMAGE_UPLOAD_SIZE_LIMIT_MB;
		}

		#[Computed]
		public function groups(): Collection {
			return ItemGroup::orderBy('name')->get();
		}

		public function render(): View {
			return view('components.dashboard.items.item-detail')
				->title("Artikel „{$this->item->name}“ bearbeiten");
		}

		public function saveItem(): void {
			$values = $this->validate(
				[
					'name'        => ['required', 'string'],
					'description' => ['string'],
					'available'   => ['boolean'],
					'visible'     => ['boolean'],
					'keepStock'   => ['boolean'],
					'stock'       => ['required_if_accepted:keepStock', Rule::unless(!$this->keepStock, ['integer', 'gt:0'])],
					'price'       => ['required', 'numeric'],
					'deposit'     => ['required', 'integer'],
					'itemGroup'   => ['sometimes', Rule::exists(ItemGroup::class, 'id')],
				]
			);

			$this->item->fill(Arr::except($values, ['keepStock', 'stock', 'itemGroup']));
			$this->item->amount = $this->keepStock ? $this->stock : 0;
			if($this->itemGroup)
				$this->item->itemGroup()->associate(ItemGroup::find($this->itemGroup));
			else
				$this->item->itemGroup()->dissociate();

			$this->item->update();
		}

		public function delete(): void {
			$this->item->delete();

			session()->flash('status.success', "Der Artikel „{$this->item->name}“ wurde gelöscht.");
			$this->redirectRoute('dashboard.items.list', navigate: true);
		}

		public function updatedImages(): void {
			// we want to store any valid images and discard invalid images only.
			// as laravels validator does not support extracting valid elements from an array if only part of the array was invalid, we have to build a separate rule for each image
			$rules = $data = [];
			foreach($this->images as $key => $image) {
				$key               = (int) $key;
				$rules["file$key"] = ['file', 'mimes:jpg,jpeg,png,webp', 'max:' . self::IMAGE_UPLOAD_SIZE_LIMIT_MB * 1024];
				$data["file$key"]  = $image;
			}
			$this->images = [];

			$validator = Validator::make($data, $rules);
			foreach($validator->valid() as $image) {
				$path = $image->store('itemImages', 'public');
				new Image(['path' => $path, 'item_id' => $this->item->id])->save();
			}

			if($validator->failed()) {
				$messageParts = [];
				foreach($validator->errors()->getMessages() as $key => $error)
					$messageParts[] = "„{$data[$key]->getClientOriginalName()}“ (" . implode('; ', $error) . ')';

				session()->flash('images.error', 'Es konnten nicht alle Dateien verarbeitet werden. Bei folgenden Dateien sind Fehler aufgetreten: ' . implode(', ', $messageParts));
			} else {
				session()->flash('images.success', (count($data) > 1 ? 'Bilder' : 'Bild') . ' erfolgreich hinzugefügt.');
			}
		}

		public function deleteImage(int $imageId): void {
			$image = Image::find($imageId);

			if($image && $image->item_id == $this->item->id) {
				$image->delete();
			}
		}

		public function setGroupImage(int $imageId): void {
			if(!$this->item->itemGroup)
				return;

			$image = Image::find($imageId);

			if($image && $image->item_id == $this->item->id) {
				$this->item->itemGroup
					->image()->associate($image)
					->save();
			}
		}
	}
