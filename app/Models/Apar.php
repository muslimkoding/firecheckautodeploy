<?php

namespace App\Models;

use App\Models\AparType;
use App\Models\Brand;
use App\Models\Building;
use App\Models\ExtinguisherCondition;
use App\Models\Floor;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Apar extends Model
{
    use HasFactory;

    protected $fillable = [
        'number_apar', 
        'location', 
        'weight_of_extinguiser', 
        'description', 
        'expired_date', 
        'qr_code', 
        'is_active', 
        'user_id', 
        'zone_id', 
        'building_id', 
        'floor_id', 
        'brand_id', 
        'apar_type_id', 
        'extinguisher_condition_id',
        'updated_by'
    ];

    protected $casts = [
        'weight_of_extinguiser' => 'decimal:2', 
        'expired_date' => 'date', 
        'is_active' => 'boolean', 
        'user_id' => 'integer', 
        'zone_id' => 'integer', 
        'building_id' => 'integer', 
        'floor_id' => 'integer', 
        'brand_id' => 'integer', 
        'apar_type_id' => 'integer', 
        'extinguisher_condition_id' => 'integer',
        'updated_by' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // accessors untuk format tampilan tanggal expired
    public function getFormattedExpiredDateAttribute()
    {
        return $this->expired_date ? $this->expired_date->format('d-m-Y') : null;
    }

    public function getFormattedWeightAttribute()
    {
        return $this->weight_of_extinguiser . ' kg';
    }

    public function getStatusBadgeAttribute()
    {
        return $this->is_active ? '<span class=""badge bg-success>Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>';
    }

    public function getExtinguisherConditionBadgeAttribute()
    {
        switch ($this->extinguisherCondition->slug) {
            case 'normal':
                return '<span class="badge bg-success">Baik</span>';
            case 'perlu-servis':
                return '<span class="badge bg-warning text-dark">Perlu Servis</span>';
            case 'rusak':
                return '<span class="badge bg-danger">Rusak</span>';
            default:
                return '<span class="badge bg-secondary">Tidak Diketahui</span>';
        }
    }

    // relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function udpatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function aparType()
    {
        return $this->belongsTo(AparType::class);
    }

    public function extinguisherCondition()
    {
        return $this->belongsTo(ExtinguisherCondition::class);
    }

    // scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeExpired($query)
    {
        return $query->where('expired_date', '<', now());
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereBetween('expired_date', [now(), now()->addDays($days)]);
    }

    /**
     * mutator untuk number apar selalu UPPERCASE
     */
    public function setNumberAparAttribute($value)
    {
        $this->attributes['number_apar'] = strtoupper($value);

        // Generate QR code otomatis dari number_apar
        if (empty($this->qr_code)) {
            // $this->attributes['qr_code'] = 'APAR-' . strtoupper($value) . '-' . time();
            $this->attributes['qr_code'] = 'ARFF-' . strtoupper($value);
        }
    }

    // =================== methode buat qrcode APAR
    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($apar) {
            // Generate qrcode otomatis jika belum ada
            if (empty($apar->qr_code)) {
                $apar->qr_code = $apar->generateQrCode();
            }
        });
    }

    /**
     * Generate QR Code dari data APAR
     */
    public function generateQrCode()
    {
        // Data yang akan diencode ke QR Code
        return 'ARFF-' . $this->number_apar;
    }

    /**
     * Accessor untuk QR Code image (SVG)
     */
    public function getQrCodeSvgAttribute()
    {
        if ($this->qr_code) {
            return QrCode::size(150)
                ->color(40, 40, 40)
                ->backgroundColor(255, 255, 255)
                ->generate($this->getQrCodeContent());
        }
        return null;
    }

    /**
     * Accessor untuk QR Code image kecil (SVG)
     */
    public function getQrCodeSmallAttribute()
    {
        if ($this->qr_code) {
            return QrCode::size(80) // Ukuran lebih kecil
                ->color(40, 40, 40)
                ->backgroundColor(255, 255, 255)
                ->generate($this->getQrCodeContent());
        }
        return null;
    }

    /**
     * Accessor untuk QR Code image (PNG - base64)
     */
    public function getQrCodePngAttribute()
    {
        if ($this->qr_code) {
            return QrCode::size(200)
                ->format('png')
                ->color(0, 0, 0)
                ->backgroundColor(255, 255, 255)
                ->generate($this->getQrCodeContent());
        }
        return null;
    }

    /**
     * Content yang akan diencode ke QR Code
     */
    public function getQrCodeContent()
    {
        // return json_encode([
        //     'id' => $this->id,
        //     'number' => $this->number_apar,
        //     'location' => $this->location,
        //     'expired_date' => $this->expired_date?->format('d-m-Y'),
        //     'weight' => $this->weight_of_extinguiser,
        //     'type' => 'APAR',
        //     'url' => route('apar.show', $this->id) // URL untuk scan
        // ]);
        // Hanya berisi value qr_code saja, bukan JSON

        // mereturn nomor apar dari table ke hasil scan
        return $this->qr_code;
    }

    /**
     * Cari APAR berdasarkan QR code
     */
    public function scopeByQrCode($query, $qrCode)
    {
        return $query->where('qr_code', $qrCode);
    }

    /**
     * Download QR Code sebagai PNG
     */
    public function downloadQrCode(Apar $apar)
    {
        if (!$apar->qr_code) {
            return redirect()->back()->with('error', 'QR Code tidak tersedia');
        }

        $qrCode = QrCode::size(400)
            ->format('png')
            ->color(0, 0, 0)
            ->backgroundColor(255, 255, 255)
            ->generate($apar->getQrCodeContent());
        
        $filename = 'qrcode-apar-' . $apar->number_apar . '.png';
        
        return response($qrCode)
            ->header('Content-type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Download QR Code sebagai SVG
     */
    public function downloadQrCodeSvg(Apar $apar)
    {
        if (!$apar->qr_code) {
            return redirect()->back()->with('error', 'QR Code tidak tersedia');
        }

        $qrCode = QrCode::size(400)
            ->color(0, 0, 0)
            ->backgroundColor(255, 255, 255)
            ->generate($apar->getQrCodeContent());
        
        $filename = 'qrcode-apar-' . $apar->number_apar . '.svg';
        
        return response($qrCode)
            ->header('Content-type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
    // =================================== batas qr code

}
