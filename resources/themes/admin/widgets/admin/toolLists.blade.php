<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">@lang('widgets.popularToolsThisWeek')</h6>
    </div>
    <div class="card-body card-height">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>@lang('common.name')</th>
                    <th class="text-center">@lang('common.thisWeek')</th>
                    <th class="text-center">@lang('common.lastWeek')</th>
                    <th class="text-center">@lang('common.allTime')</th>
                </tr>
            </thead>
            @forelse ($tools as $tool)
                <tr>
                    <td><p class="mb-0">{{ $tool->name }}</p></td>
                    <td class="text-center">{{ $tool->this_week_count }}</td>
                    <td class="text-center">{{ $tool->last_week_count }}</td>
                    <td class="text-center">{{ $tool->views_count }}</td>
                </tr>
            @empty
                <tr>
                    <td class="text-center" colspan="22">@lang('common.noRecordsFund')</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
