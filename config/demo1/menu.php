<?php

use App\Core\Adapters\Theme;

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
                        'path'   => '#',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                    [
                        'title'  => 'Mechanical Court',
                        'path'   => '#',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                    [
                        'title'  => 'Appearance',
                        'path'   => '#',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                ]
            ]
        ],
        [
            'title' => 'Penugasan',
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
                        'title'  => 'Pengajuan',
                        'path'   => '#',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                    [
                        'title'  => 'Approval',
                        'path'   => '#',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                    [
                        'title'  => 'List Tugas',
                        'path'   => '#',
                        'bullet' => '<span class="bullet bullet-dot"></span>',
                    ],
                ]
            ]
        ],
        [
            'title' => 'Pertandingan',
            'icon' => [
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
                        'path'   => '#',
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
