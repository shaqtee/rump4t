<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            <div class="">
                <div class="form-group">
                    <label for="t_community_id">Community</label>
                    @error('t_community_id')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <select name="t_community_id" id="t_community_id" class="form-control select2" required autofocus>
                        <option label="Choose one" disabled selected>Select Community</option>
                        @foreach ($community as $cmnty)
                            <option disabled value="{{ $cmnty->id }}"
                                @if (old('t_community_id', isset($event) ? $event->t_community_id : '') == $cmnty->id) selected @endif>
                                {{ $cmnty->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="title">title</label>
                    @error('title')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <input readonly type="text" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title', isset($event) ? $event->title : '') }}" name="title" id="title"
                        placeholder="title" required autofocus>
                </div>
                <div class="form-group">
                    @if (isset($event))
                        <label for="image">Image</label>
                        @error('image')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <label for="">Your image</label>
                        <img class="img-thumbnail wd-100p wd-sm-200 mb-3" src="{{ isset($event) ? $event->image : '' }}"
                            style="display: block;">
                    @endif
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    @error('description')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <input readonly type="text" class="form-control @error('description') is-invalid @enderror"
                        value="{{ old('description', isset($event) ? $event->description : '') }}" name="description"
                        id="description" placeholder="Description" required autofocus>
                </div>
                {{-- <div class="form-group">
                            <label for="location">Location</label>
                            @error('location')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input readonly type="text" class="form-control @error('location') is-invalid @enderror"  value="{{ old('location', isset($event) ? $event->location : '') }}" name="location" id="location" placeholder="Location" required autofocus>
                        </div> --}}
                <div class="form-group">
                    <label for="m_golf_course_id">Golf Course</label>
                    @error('m_golf_course_id')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <select name="m_golf_course_id" id="m_golf_course_id" class="form-control select2" required
                        autofocus>
                        <option label="Choose one" disabled selected>Select Golf Course</option>
                        @foreach ($golfCourse as $gc)
                            <option disabled value="{{ $gc->id }}"
                                @if (old('m_golf_course_id', isset($event) ? $event->m_golf_course_id : '') == $gc->id) selected @endif>
                                {{ $gc->name, $gc->address }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    @error('price')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <input readonly type="text" class="form-control @error('price') is-invalid @enderror"
                        value="{{ old('price', isset($event) ? $event->price : '') }}" name="price" id="price"
                        placeholder="Price" required autofocus>
                </div>
                <div class="form-group">
                    <label for="type_scoring">Type Scoring</label>
                    @error('type_scoring')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <select name="type_scoring" id="type_scoring" class="form-control select2" required autofocus>
                        <option label="Choose one" disabled selected>Select Type</option>
                        @foreach ($type_scoring as $ts)
                            <option disabled value="{{ $ts->value2 }}"
                                @if (old('type_scoring', isset($event) ? $event->type_scoring : '') == $ts->value2) selected @endif>
                                {{ $ts->value1 }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="Period">Period</label>
                    @error('Period')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <select name="period" id="period" class="form-control select2" required autofocus>
                        <option label="Choose one" disabled selected>Select Period</option>
                        @foreach ($period as $p)
                            <option disabled value="{{ $p->value2 }}"
                                @if (old('period', isset($event) ? $event->period : '') == $p->value2) selected @endif>
                                {{ $p->value1 }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="play_date_start">Start Play Date</label>
                    @error('play_date_start')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                        </div>
                        <input readonly class="form-control fc-datepicker" id="datetimepicker" name="play_date_start"
                            type="text"
                            value="{{ old('play_date_start', isset($event) ? $event->play_date_start : '') }}"
                            placeholder="Start Date Play" required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label for="play_date_end">End Play Date</label>
                    @error('play_date_end')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                        </div>
                        <input readonly class="form-control fc-datepicker" id="datetimepicker2" name="play_date_end"
                            type="text"
                            value="{{ old('play_date_end', isset($event) ? $event->play_date_end : '') }}"
                            placeholder="End Date Play" required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label for="close_registration">Close Regists</label>
                    @error('close_registration')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                        </div>
                        <input readonly class="form-control fc-datepicker" name="close_registration" type="text"
                            value="{{ old('close_registration', isset($event) ? $event->close_registration : '') }}"
                            placeholder="Close Regists" required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label for="t_winner_category_id">Winner Category</label>
                    @error('t_winner_category_id')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <div class="row">
                        @foreach ($master_wc as $mwc)
                            <div class="col-lg-3">
                                <label class="ckbox">
                                    <input disabled name="t_winner_category_id[]" value="{{ $mwc->id }}"
                                        {{ isset($winner_category) && in_array($mwc->id, array_column($winner_category, 't_winner_category_id')) ? 'checked' : '' }}
                                        type="checkbox">
                                    <span>{{ $mwc->name }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label for="t_tee_man_id">Tee For Man</label>
                    @error('t_tee_man_id')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <select name="t_tee_man_id" id="t_tee_man_id" class="form-control select2" required autofocus>
                        <option label="Choose one" disabled selected>Select Tee</option>
                        @foreach ($tee_box as $tb)
                            <option disabled value="{{ $tb->id }}"
                                @if (old('t_tee_man_id', isset($event) ? $event->t_tee_man_id : '') == $tb->id) selected @endif>
                                {{ $tb->tee_type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="t_tee_ladies_id">Tee For Ladies</label>
                    @error('t_tee_ladies_id')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <select name="t_tee_ladies_id" id="t_tee_ladies_id" class="form-control select2" required
                        autofocus>
                        <option label="Choose one" disabled selected>Select Tee</option>
                        @foreach ($tee_box as $tb)
                            <option disabled value="{{ $tb->id }}"
                                @if (old('t_tee_ladies_id', isset($event) ? $event->t_tee_ladies_id : '') == $tb->id) selected @endif>
                                {{ $tb->tee_type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="m_round_type_id">Round Type</label>
                    @error('m_round_type_id')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <select name="m_round_type_id" id="m_round_type_id" class="form-control select2" required
                        autofocus>
                        <option label="Choose one" disabled selected>Select Round Type</option>
                        @foreach ($holes as $h)
                            <option disabled value="{{ $h->id }}"
                                @if (old('m_round_type_id', isset($event) ? $event->m_round_type_id : '') == $h->id) selected @endif>
                                {{ $h->value1 }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="active">Active</label>
                    @error('active')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <div class="row">
                        <div class="col">
                            <input readonly class="form-control"
                                value="{{ old('active', isset($event) && $event->active) == '1' ? 'Active' : 'Deactive' }}"
                                name="active" type="text" required autofocus>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
