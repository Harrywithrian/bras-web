<?php

use App\Core\Adapters\Theme;


// Debugbar::info('test');

return [
    // Refer to config/global/menu.php

    // Main Menu
    'main' => [
        [
            'title' => 'Home',
            'icon' => [
                'font' => '<i class="bi bi-house fs-2"></i>',
            ],
            'path'   => '',
        ],
        [
            'title' => 'Admin Panel',
            'icon' => [
                'font' => '<i class="bi bi-folder fs-2"></i>',
            ],
            'classes' => ['item' => 'menu-accordion'],
            'attributes' => [
                "data-kt-menu-trigger" => "click",
            ],
            'sub' => [
                'class' => 'menu-sub-accordion menu-active-bg',
                'items' => [
                    [
                        'title'  => 'Manajemen User',
                        'path'   => '#',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                    [
                        'title'  => 'Menu',
                        'path'   => '#',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                    [
                        'title'  => 'Role',
                        'path'   => '#',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                    [
                        'title'  => 'Permission',
                        'path'   => '#',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                ]
            ]
        ],
        [
            'title' => 'Master Data',
            'icon' => [
                'font' => '<i class="bi bi-file-earmark fs-2"></i>',
            ],
            'classes' => ['item' => 'menu-accordion'],
            'attributes' => [
                "data-kt-menu-trigger" => "click",
            ],
            'sub' => [
                'class' => 'menu-sub-accordion menu-active-bg',
                'items' => [
                    [
                        'title'  => 'Provinsi',
                        'path'   => 'region.index',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                    [
                        'title'  => 'Lokasi Pertandingan',
                        'path'   => 'location.index',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                    [
                        'title'  => 'Lisensi',
                        'path'   => 'license.index',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                    [
                        'title'  => 'Pelanggaran',
                        'path'   => 'violation.index',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                    [
                        'title'  => 'IOT',
                        'path'   => 'iot.index',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                ]
            ]
        ],
        [
            'title' => 'Template',
            'icon' => [
                'font' => '<i class="bi bi-clipboard-check fs-2"></i>',
            ],
            'classes' => ['item' => 'menu-accordion'],
            'attributes' => [
                "data-kt-menu-trigger" => "click",
            ],
            'sub' => [
                'class' => 'menu-sub-accordion menu-active-bg',
                'items' => [
                    [
                        'title'  => 'Game Management',
                        'path'   => 'm-game-management.index',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                    [
                        'title'  => 'Mechanical Court',
                        'path'   => 'm-mechanical-court.index',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                    [
                        'title'  => 'Appearance',
                        'path'   => 'm-appearance.index',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                ]
            ]
        ],
        [
            'title' => 'Approval User',
            'path'   => 't-approval.index',
            'icon' => [
                'font' => '<i class="bi bi-person-square fs-2"></i>',
            ]
        ],
        [
            'title' => 'Rekomendasi Dan Surat Tugas',
            'icon' => [
                'font' => '<i class="bi bi-calendar2-check fs-2"></i>',
            ],
            'classes' => ['item' => 'menu-accordion'],
            'attributes' => [
                "data-kt-menu-trigger" => "click",
            ],
            'sub' => [
                'class' => 'menu-sub-accordion menu-active-bg',
                'items' => [
                    [
                        'title'  => 'Pengajuan Event',
                        'path'   => 't-event.index',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                    [
                        'title'  => 'Approval Event',
                        'path'   => 't-event-approval.index',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                    [
                        'title'  => 'Surat Tugas',
                        'path'   => '#',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                ]
            ]
        ],
        [
            'title' => 'Tugas Pertandingan',
            'path'  => 't-match.index-event',
            'icon'  => [
                'font' => '<i class="bi bi-layout-text-sidebar-reverse fs-2"></i>',
            ]
        ],
        [
            'title' => 'Report',
            'icon' => [
                'font' => '<i class="bi bi-pie-chart fs-2"></i>',
            ],
            'classes' => ['item' => 'menu-accordion'],
            'attributes' => [
                "data-kt-menu-trigger" => "click",
            ],
            'sub' => [
                'class' => 'menu-sub-accordion menu-active-bg',
                'items' => [
                    [
                        'title'  => 'Evaluasi Wasit',
                        'path'   => '#',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                    [
                        'title'  => 'Evaluasi Pertandingan',
                        'path'   => 'report-pertandingan.index-event',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                ]
            ]
        ],
    ],

    // Horizontal menu
    'horizontal' => [
    ],
];
