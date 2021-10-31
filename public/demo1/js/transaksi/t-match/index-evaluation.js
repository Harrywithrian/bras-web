$(document).ready(() => {

  // evaluation data
  let evaluation = new Map()
  let currentEvaluation = {
    addedOn: null,
    quarter: null,
    wasit: null, // object wasit
    time: null, // string time
    evaluation: {
      callAnalyis: null,
      position: null,
      zoneBox: null,
      callType: null,
      iot: null
    } // object evaluation
  }

  // timer selector
  const timerSelector = {
    timerDisplay: $('#timer-display'),
    timerControl: $('#timer-control'),
    timerStart: $('#timer-start'),
    timerStop: $('#timer-stop'),
    timerPause: $('#timer-pause'),
  }

  // init timer
  const timer = new Timer({
    seconds: 0,
    minutes: 10
  }, timerSelector)

  // init display
  timer.displayTime()

  // init timer control
  if (timer.isInit()) {
    timerSelector.timerControl.addClass('show-content')
    timerSelector.timerControl.removeClass('hide-content')
    $('#add-evaluation').addClass('show-content')
    $('#add-evaluation').removeClass('hide-content')
  }

  // start timer
  $(document).on('click', '#timer-start', function () {
    timer.start()
  })

  // pause timer
  $(document).on('click', '#timer-pause', function () {
    timer.pause((info) => {
      currentEvaluation.time = info.formattedTime
    })
  })

  // stop timer
  $(document).on('click', '#timer-stop', function () {
    timer.stop()
  })

  $("#quarter-picker").select2();
  // pick quarter
  $("#quarter-picker").on('select2:select', function (e) {
    currentEvaluation.quarter = e.params.data.id
  })

  // pick referee
  const pickReferee = () => {
    currentEvaluation.wasit = $("input[type=radio][name=referee]:checked").data('value')
  }

  // evaluate value whenever violation click
  const pickPlayCall = () => {
    const callAnalyis = $("input[type=radio][name=call_analysis]:checked").data('value')
    const position = $("input[type=radio][name=position]:checked").data('value')
    const zoneBox = $("input[type=radio][name=zone_box]:checked").data('value')
    const callType = $("input[type=radio][name=call_type]:checked").data('value')
    const iot = $("input[type=radio][name=iot]:checked").data('value')

    // console.log(callAnalyis, position, zoneBox, callType, iot)

    // const evaluationPoint = callAnalyis - (position + zoneBox + callType + iot)
    // console.log(evaluationPoint)

    // const evaluation = {
    //   callAnalysis: {
    //     id:
    //   }
    // }
    currentEvaluation.evaluation = {
      callAnalyis,
      position,
      zoneBox,
      callType,
      iot
    }
  }

  // timer handler


  // referee picker handler
  $("input[type=radio][name=referee]").on("click", pickReferee)

  // violation picker handler
  $("input[type=radio][name=call_analysis]").on("click", pickPlayCall)
  $("input[type=radio][name=position]").on("click", pickPlayCall)
  $("input[type=radio][name=zone_box]").on("click", pickPlayCall)
  $("input[type=radio][name=call_type]").on("click", pickPlayCall)
  $("input[type=radio][name=iot]").on("click", pickPlayCall)


  const clearSelection = () => {
    $("input[type=radio][name=referee]:checked").prop("checked", false);
    $("input[type=radio][name=call_analysis]:checked").prop("checked", false);
    $("input[type=radio][name=position]:checked").prop("checked", false);
    $("input[type=radio][name=zone_box]:checked").prop("checked", false);
    $("input[type=radio][name=call_type]:checked").prop("checked", false);
    $("input[type=radio][name=iot]:checked").prop("checked", false);

    currentEvaluation.wasit = null
    currentEvaluation.time = null
    currentEvaluation.evaluation.callAnalyis = null
    currentEvaluation.evaluation.position = null
    currentEvaluation.evaluation.zoneBox = null
    currentEvaluation.evaluation.callType = null
    currentEvaluation.evaluation.iot = null
  }



  // data table
  const dt = $('#content-table').DataTable({
    // ajax: "/t-match/get-event",
    bFilter: false,
    processing: false,
    serverSide: false,
    ordering: false,
    order: [
      [0, 'desc']
    ],
    stateSave: true,
    searching: true,
    language: {
      sEmptyTable: "Tidak ada data yang tersedia pada tabel ini",
      sProcessing: "Sedang memproses...",
      sLengthMenu: "Tampilkan data _MENU_",
      sZeroRecords: "Tidak ditemukan data yang sesuai",
      sInfo: "_START_ - _END_ dari _TOTAL_",
      sInfoEmpty: "0 - 0 dari 0",
      sInfoFiltered: "(disaring dari _MAX_ data keseluruhan)",
      sInfoPostFix: "",
      sSearch: "",
      searchPlaceholder: "Cari ...",
      sUrl: "",
      oPaginate: {
        sFirst: "pertama",
        sPrevious: "sebelumnya",
        sNext: "selanjutnya",
        sLast: "terakhir"
      }
    },
    columnDefs: [
      {
        targets: [0],
        visible: false,
        searchable: false
      }
    ]
    // columns: [
    //   {
    //     data: "row",
    //     name: "row",
    //     orderable: false,
    //     searchable: false
    //   },
    //   {
    //     data: 'action',
    //     name: 'action',
    //     orderable: false,
    //     searchable: false
    //   },
    //   {
    //     data: 'quarter',
    //     name: 'quarter',
    //     orderable: true,
    //     searchable: true
    //   },
    //   {
    //     data: 'name',
    //     name: 'name',
    //     orderable: true,
    //     searchable: true
    //   },
    //   {
    //     data: 'time',
    //     name: 'time',
    //     orderable: true,
    //     searchable: true
    //   },
    //   {
    //     data: 'callAnalysis',
    //     name: 'callAnalysis',
    //     orderable: true,
    //     searchable: true
    //   },
    //   {
    //     data: 'posisi',
    //     name: 'posisi',
    //     orderable: true,
    //     searchable: true
    //   },
    //   {
    //     data: 'zoneBox',
    //     name: 'zoneBox',
    //     orderable: true,
    //     searchable: true
    //   },
    //   {
    //     data: 'callType',
    //     name: 'callType',
    //     orderable: true,
    //     searchable: true
    //   },
    //   {
    //     data: 'iot',
    //     name: 'iot',
    //     orderable: true,
    //     searchable: true
    //   }
    // ]
  });


  var counter = 1
  // add row
  const addRow = (data) => {
    dt.row.add([
      data.addedOn,
      counter,
      'act',
      data.quarter,
      data.name,
      data.time,
      data.callAnalyis,
      data.position,
      data.zoneBox,
      data.callType,
      data.iot,
    ]).draw(false);
    counter++
  }

  // add evaluation
  $(document).on('click', '#add-evaluation', function () {
    currentEvaluation.addedOn = Date.now()
    console.log(currentEvaluation)

    // quarter: null,
    //   wasit: null, // object wasit
    //     time: null, // string time
    //       evaluation: {
    //   callAnalyis: null,
    //     position: null,
    //       zoneBox: null,
    //         callType: null,
    //           iot: null

    // check integrity data
    if (!currentEvaluation.quarter) {
      Swal.fire({
        text: "Error, quarter belum dipilih!",
        icon: "error",
        buttonsStyling: false,
        confirmButtonText: "Ok",
        customClass: {
          confirmButton: "btn btn-primary"
        }
      });
    } else if (!currentEvaluation.time) {
      Swal.fire({
        text: "Error, pause timer terlebih dahulu",
        icon: "error",
        buttonsStyling: false,
        confirmButtonText: "Ok",
        customClass: {
          confirmButton: "btn btn-primary"
        }
      });
    }

    else if (!currentEvaluation.wasit) {
      Swal.fire({
        text: "Error, pilih wasit terlebih dahulu",
        icon: "error",
        buttonsStyling: false,
        confirmButtonText: "Ok",
        customClass: {
          confirmButton: "btn btn-primary"
        }
      });
    }

    else if (!currentEvaluation.evaluation.callAnalyis) {
      Swal.fire({
        text: "Error, pilih call analysis terlebih dahulu",
        icon: "error",
        buttonsStyling: false,
        confirmButtonText: "Ok",
        customClass: {
          confirmButton: "btn btn-primary"
        }
      });
    }

    else if (!currentEvaluation.evaluation.position) {
      Swal.fire({
        text: "Error, pilih position terlebih dahulu",
        icon: "error",
        buttonsStyling: false,
        confirmButtonText: "Ok",
        customClass: {
          confirmButton: "btn btn-primary"
        }
      });
    }

    else if (!currentEvaluation.evaluation.zoneBox) {
      Swal.fire({
        text: "Error, pilih zone box terlebih dahulu",
        icon: "error",
        buttonsStyling: false,
        confirmButtonText: "Ok",
        customClass: {
          confirmButton: "btn btn-primary"
        }
      });
    }

    else if (!currentEvaluation.evaluation.callType) {
      Swal.fire({
        text: "Error, pilih call type dahulu",
        icon: "error",
        buttonsStyling: false,
        confirmButtonText: "Ok",
        customClass: {
          confirmButton: "btn btn-primary"
        }
      });
    }

    else if (!currentEvaluation.evaluation.iot) {
      Swal.fire({
        text: "Error, pilih iot terlebih dahulu",
        icon: "error",
        buttonsStyling: false,
        confirmButtonText: "Ok",
        customClass: {
          confirmButton: "btn btn-primary"
        }
      });
    }


    if (
      currentEvaluation.quarter &&
      currentEvaluation.time &&
      currentEvaluation.wasit &&
      currentEvaluation.evaluation.callAnalyis &&
      currentEvaluation.evaluation.position &&
      currentEvaluation.evaluation.zoneBox &&
      currentEvaluation.evaluation.callType &&
      currentEvaluation.evaluation.iot
    ) {
      // add current evaluation to mapped data
      evaluation.set(currentEvaluation.addedOn, currentEvaluation)

      // add to local storage
      localStorage.setItem()

      // add to datatable row
      addRow({
        addedOn: currentEvaluation.addedOn,
        quarter: currentEvaluation.quarter,
        name: currentEvaluation.wasit.name,
        time: currentEvaluation.time,
        callAnalyis: currentEvaluation.evaluation.callAnalyis.text,
        position: currentEvaluation.evaluation.position.text,
        zoneBox: currentEvaluation.evaluation.zoneBox.text,
        callType: currentEvaluation.evaluation.callType.text,
        iot: currentEvaluation.evaluation.iot.text,
      })

      Swal.fire({
        text: "Berhasil ditambahkan",
        icon: "success",
        buttonsStyling: false,
        confirmButtonText: "Ok",
        customClass: {
          confirmButton: "btn btn-primary"
        }
      });

      // clear selection
      clearSelection()
    }


  })

})