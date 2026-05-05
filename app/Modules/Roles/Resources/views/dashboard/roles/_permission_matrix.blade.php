{{--
    Permission Matrix partial.
    Variables:
      $permissions       - Collection of all Permission models
      $selectedPerms     - array of selected permission IDs (default: [] for create, $rolePermissions for edit)
--}}
@if ($permissions->count())
    @php
        $grouped = $permissions->groupBy(function ($p) {
            foreach (['contact-messages', 'education-levels'] as $multi) {
                if (str_starts_with($p->name, $multi)) return $multi;
            }
            return explode('-', $p->name)[0] ?? 'other';
        });
        $crudActions = ['create', 'read', 'update', 'delete'];
    @endphp

    <div class="form-group col-12 mt-1">
        <label class="font-weight-bold">{{ __('dashboard.permissions') }}</label>
        <div class="table-responsive mt-2">
            <table class="table table-bordered table-sm permission-matrix">
                <thead>
                    <tr>
                        <th style="min-width:130px">{{ __('dashboard.module') }}</th>
                        @foreach ($crudActions as $action)
                            <th>{{ ucfirst(__('dashboard.'.$action)) }}</th>
                        @endforeach
                        <th>{{ __('dashboard.other') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($grouped as $module => $modulePerms)
                        <tr>
                            <td><strong>{{ ucfirst(str_replace('-', ' ', $module)) }}</strong></td>

                            @foreach ($crudActions as $action)
                                @php $perm = $modulePerms->firstWhere('name', "$module-$action"); @endphp
                                <td>
                                    @if ($perm)
                                        <input type="checkbox" name="permissions[]"
                                               value="{{ $perm->id }}"
                                               {{ in_array($perm->id, old('permissions', $selectedPerms)) ? 'checked' : '' }}>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            @endforeach

                            <td>
                                @php
                                    $others = $modulePerms->filter(function ($p) use ($crudActions, $module) {
                                        return ! in_array(str_replace($module.'-', '', $p->name), $crudActions);
                                    });
                                @endphp
                                @forelse ($others as $p)
                                    <div class="d-flex align-items-center gap-1 mb-1">
                                        <input type="checkbox" name="permissions[]"
                                               id="p-{{ $p->id }}" value="{{ $p->id }}"
                                               {{ in_array($p->id, old('permissions', $selectedPerms)) ? 'checked' : '' }}>
                                        <label for="p-{{ $p->id }}" class="mb-0 small">{{ $p->display_name ?: $p->name }}</label>
                                    </div>
                                @empty
                                    <span class="text-muted">—</span>
                                @endforelse
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
