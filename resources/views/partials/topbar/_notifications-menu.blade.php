<?php
    // Content library
    $notifUser = \Illuminate\Support\Facades\Auth::id();
    $notif     = \App\Models\Transaksi\TNotification::where('user', '=', $notifUser)->where('status', '=', 0)->orderBy('createdon', 'DESC')->limit(10)->get()->all();
    $logs      = null;
    if ($notif) {
        foreach ($notif as $item) {
            if ($item['type'] == 1) {
                $event = \App\Models\Transaksi\TEvent::find($item['id_event_match']);
                $logs[] = [
                    'id' => $item->id,
                    'code' => 'Event',
                    'state' => 'info',
                    'message' => $event->nama,
                ];
            } else if ($item['type'] == 2) {
                $match = \App\Models\Transaksi\TMatch::find($item['id_event_match']);
                $logs[] = [
                    'id' => $item->id,
                    'code' => 'Match',
                    'state' => 'primary',
                    'message' => $match->nama,
                ];
            }
        }
    }
?>

<!--begin::Menu-->
<div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true">
	<!--begin::Heading-->
    <div class="d-flex flex-column bgi-no-repeat rounded-top" style="background-image:url('{{ asset(theme()->getMediaUrlPath() . 'misc/pattern-1.jpg') }}')">
        <!--begin::Title-->
        <h3 class="text-white fw-bold px-9 mt-10 mb-6">
            Notifications
        </h3>
        <!--end::Title-->

    </div>
	<!--end::Heading-->

    <!--begin::Tab content-->
    <div class="tab-content">
        <!--begin::Items-->
        <div class="scroll-y mh-325px my-5 px-8">
        <?php if ($logs) { ?>
            <?php foreach($logs as $log):?>
            <!--begin::Item-->
                <div class="d-flex flex-stack py-4">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center me-2">
                        <!--begin::Code-->
                        <span class="w-70px badge badge-light-{{ $log['state'] }} me-4">{{ $log['code'] }}</span>
                        <!--end::Code-->

                        <!--begin::Title-->
                        @if ($log['code'] == 'Event')
                            <a href="{{ route('notifikasi.event', $log['id']) }}" class="text-gray-800 text-hover-primary fw-bold">{{ $log['message'] }}</a>
                        @elseif ($log['code'] == 'Match')
                            <a href="{{ route('notifikasi.match', $log['id']) }}" class="text-gray-800 text-hover-primary fw-bold">{{ $log['message'] }}</a>
                        @endif
                        <!--end::Title-->
                    </div>
                    <!--end::Section-->
                </div>
                <!--end::Item-->
            <?php endforeach?>
        <?php } ?>
        </div>
        <!--end::Items-->

        <!--begin::View more-->
        <?php if ($logs) { ?>
            <div class="py-3 text-center border-top">
                <a href="{{ theme()->getPageUrl('pages/profile/activity') }}" class="btn btn-color-gray-600 btn-active-color-primary">
                    View All
                    {!! theme()->getSvgIcon("icons/duotune/arrows/arr064.svg", "svg-icon-5") !!}
                </a>
            </div>
        <?php } else { ?>
            <h3 class="text-gray-500" style="margin-top:-10px !important; margin-bottom:20px !important;"><center>Tidak Ada Notifikasi</center></h3>
        <?php } ?>
        <!--end::View more-->
    </div>
    <!--end::Tab content-->
</div>
<!--end::Menu-->
