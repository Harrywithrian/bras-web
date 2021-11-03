$(document).ready(() => {


  // init evaluation
  const evaluation = new Evaluation()

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
      evaluation.setTime(info.formattedTime)
    })
  })

  // stop timer
  $(document).on('click', '#timer-stop', function () {
    timer.stop()
  })

  $("#quarter-picker").select2();
  // pick quarter
  $("#quarter-picker").on('select2:select', function (e) {
    evaluation.setQuarter(e.params.data.id)
  })

  // pick referee
  const pickReferee = () => {
    evaluation.setWasit($("input[type=radio][name=referee]:checked").data('value'))
  }

  // evaluate value whenever violation click
  const pickPlayCall = () => {
    const callAnalysis = $("input[type=radio][name=call_analysis]:checked").data('value')
    const position = $("input[type=radio][name=position]:checked").data('value')
    const zoneBox = $("input[type=radio][name=zone_box]:checked").data('value')
    const callType = $("input[type=radio][name=call_type]:checked").data('value')
    const iot = $("input[type=radio][name=iot]:checked").data('value')

    evaluation.setCallAnalysis(callAnalysis)
    evaluation.setPosition(position)
    evaluation.setZoneBox(zoneBox)
    evaluation.setCallType(callType)
    evaluation.setIot(iot)
  }

  // referee picker handler
  $("input[type=radio][name=referee]").on("click", pickReferee)

  // violation picker handler
  $("input[type=radio][name=call_analysis]").on("click", pickPlayCall)
  $("input[type=radio][name=position]").on("click", pickPlayCall)
  $("input[type=radio][name=zone_box]").on("click", pickPlayCall)
  $("input[type=radio][name=call_type]").on("click", pickPlayCall)
  $("input[type=radio][name=iot]").on("click", pickPlayCall)

  // clear selection
  const clearSelection = () => {
    $("input[type=radio][name=referee]:checked").prop("checked", false);
    $("input[type=radio][name=call_analysis]:checked").prop("checked", false);
    $("input[type=radio][name=position]:checked").prop("checked", false);
    $("input[type=radio][name=zone_box]:checked").prop("checked", false);
    $("input[type=radio][name=call_type]:checked").prop("checked", false);
    $("input[type=radio][name=iot]:checked").prop("checked", false);

    evaluation.setWasit(null)
    evaluation.setTime(null)
    evaluation.setCallAnalysis(null)
    evaluation.setPosition(null)
    evaluation.setZoneBox(null)
    evaluation.setCallType(null)
    evaluation.setIot(null)
  }

  // console.log([...evaluation.getEvaluation().values()])

  // data table
  const dt = $('#content-table').DataTable({
    // ajax: "/t-match/get-event",
    bFilter: false,
    processing: false,
    serverSide: false,
    ordering: false,
    order: [
      // [0, 'desc']
    ],
    data: [...evaluation.getEvaluation().values()],
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
    // columnDefs: [
    //   {
    //     data: 'action',
    //     render: (row) => {
    //       return 'tes'
    //     }
    //   },
    // ],
    columns: [
      {
        data: 'action',
        render: (data) => {
          return 'tes'
        }
      },
      {
        data: 'quarter',
        render: (data) => {
          console.log('quarter', data)
          return data || '-'
        }
      },
      {
        data: 'wasit.name',
      },
      {
        data: 'time',
      },
      {
        data: 'evaluation.callAnalysis.text',
      },
      {
        data: 'evaluation.position.text',
      },
      {
        data: 'evaluation.zoneBox.text',
      },
      {
        data: 'evaluation.callType.text',
      },
      {
        data: 'evaluation.iot.text',
      }
    ]
  });


  var counter = 1
  // add row
  const addRow = (data) => {
    console.log('added data', data)
    dt.row.add(data).draw(false);
    counter++
  }

  // show alert
  const showAlert = (message, icon = 'error') => {
    Swal.fire({
      text: message,
      icon: icon,
      buttonsStyling: false,
      confirmButtonText: "Ok",
      customClass: {
        confirmButton: "btn btn-primary"
      }
    });
  }

  // add evaluation
  $(document).on('click', '#add-evaluation', function () {
    evaluation.setAddedOn(Date.now())
    console.log(evaluation.getCurrentEvaluation())

    evaluation.validate(
      (currentEvaluationData) => {
        // addRow({
        //   quarter: currentEvaluationData.quarter,
        //   name: currentEvaluationData.wasit.name,
        //   time: currentEvaluationData.time,
        //   callAnalysis: currentEvaluationData.evaluation.callAnalysis.text,
        //   position: currentEvaluationData.evaluation.position.text,
        //   zoneBox: currentEvaluationData.evaluation.zoneBox.text,
        //   callType: currentEvaluationData.evaluation.callType.text,
        //   iot: currentEvaluationData.evaluation.iot.text,
        // })
        addRow(currentEvaluationData)

        // show message success
        showAlert('Berhasil ditambahkan', 'success')

        // clear selection
        clearSelection()
      }, (errorMessage) => {
        showAlert(errorMessage)
      })

  })

})