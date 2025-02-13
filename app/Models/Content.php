<?php

	namespace App\Models;

	use App\Util\Markdown;
	use DateTime;
	use Illuminate\Database\Eloquent\Model;

	/**
	 * @property int      $id
	 * @property string   $name
	 * @property string   $description
	 * @property Markdown $content
	 * @property DateTime $created_at
	 * @property DateTime $updated_at
	 */
	class Content extends Model {
		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array<string>
		 */
		protected $fillable = [
			'name',
			'description',
			'content',
		];

		public function render(): string {
			return $this->content->render();
		}

		public static function fromName(string $name): ?static {
			return static::where('name', $name)->first();
		}

		/**
		 * Get the attributes that should be cast.
		 *
		 * @return array<string, string>
		 */
		protected function casts(): array {
			return [
				'content' => Markdown::class,
			];
		}
	}
