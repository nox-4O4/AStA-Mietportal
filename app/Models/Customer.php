<?php

	namespace App\Models;

	use App\Events\InvoiceDataChanged;
	use Carbon\CarbonImmutable;
	use Illuminate\Database\Eloquent\Casts\Attribute;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\HasMany;
	use Illuminate\Notifications\Notifiable;
	use Illuminate\Notifications\Notification;

	/**
	 * @property int              $id
	 * @property string           $forename
	 * @property string           $surname
	 * @property ?string          $legalname
	 * @property ?string          $street
	 * @property ?string          $number
	 * @property ?string          $zipcode
	 * @property ?string          $city
	 * @property string           $email
	 * @property ?string          $mobile
	 * @property ?CarbonImmutable $created_at
	 * @property ?CarbonImmutable $updated_at
	 *
	 * @property-read string      $invoiceHash      See {@see Customer::invoiceHash()} for getter.
	 */
	class Customer extends Model {
		use Notifiable;

		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array<string>
		 */
		protected $fillable = [
			'forename',
			'surname',
			'legalname',
			'street',
			'number',
			'zipcode',
			'city',
			'email',
			'mobile',
		];

		static array $invoiceRelevantData = [
			'forename',
			'surname',
			'legalname',
			'street',
			'number',
			'zipcode',
			'city',
		];

		protected static function booted(): void {
			static::saving(function (Customer $customer): void {
				foreach(static::$invoiceRelevantData as $field) {
					if($customer->$field != $customer->getOriginal($field)) {
						foreach($customer->orders as $order)
							$order->queueEvent(InvoiceDataChanged::class);

						break;
					}
				}
			});
		}

		/**
		 * Change the email recipient so that it contains the name as well.
		 *
		 * @see MailChannel::getRecipients()
		 */
		public function routeNotificationForMail(Notification $notification): array|string {
			return [$this->email => $this->name];
		}

		public function orders(): HasMany {
			return $this->hasMany(Order::class)->orderBy('id')->chaperone();
		}

		public function invoices(): HasMany {
			return $this->hasMany(Invoice::class)->orderBy('id')->chaperone();
		}

		public function name(): Attribute {
			return Attribute::get(fn() => $this->forename . ' ' . $this->surname);
		}

		public function invoiceHash(): Attribute {
			return Attribute::get(fn() => sha1(implode("\0", $this->only(static::$invoiceRelevantData))));
		}

	}
