<div class="row match-height">
    @foreach ($counterStats as $counter)
        <div class="col-sm-6 col-lg-3">
            <div class="card mb-4 pt-2 pb-2 text-white {{ $counter['class'] }}">
                <div class="card-body pb-0">
                    <div class="text-end">
                        <i class="lni lni-32 lni-{{ $counter['icon'] }}"></i>
                    </div>
                    <h6 class="h4 pt-5">
                        {{ $counter['value'] }}
                    </h6>
                    <p>{{ $counter['label'] }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>
