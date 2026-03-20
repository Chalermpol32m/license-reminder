use App\Models\Driver;

public function driver()
{
    return $this->belongsTo(Driver::class);
}