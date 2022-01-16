$(document).ready(() => {


  // init quarter 
  const quarter = new Quarter()

  // init play calling
  const playCalling = new PlayCalling()

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
    $('#add-play-calling').addClass('show-content')
    $('#add-play-calling').removeClass('hide-content')
  }

  // start timer
  $(document).on('click', '#timer-start', function () {
    timer.start()
  })

  // pause timer
  $(document).on('click', '#timer-pause', function () {
    timer.pause((info) => {
      playCalling.setTime(info.formattedTime)
    })
  })

  // stop timer
  $(document).on('click', '#timer-stop', function () {
    Swal.fire({
      text: 'Apakah anda akan mereset timer?',
      icon: 'warning',
      buttonsStyling: false,
      confirmButtonText: "Ok",
      showCancelButton: true,
      customClass: {
        confirmButton: "btn btn-primary",
        cancelButton: "btn btn-warning",
      }
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        timer.stop()
      }
    });
  })

  $("#quarter-picker").val(quarter.getQuarter());
  $("#quarter-picker").trigger('change');
  $("#quarter-picker").select2();
  // set last quarter
  playCalling.setQuarter(quarter.getQuarter())
  // $('#mySelect2').val('1'); // Select the option with a value of '1'
  // $('#mySelect2').trigger('change'); // Notify any JS components that the value changed
  // pick quarter
  $("#quarter-picker").on('select2:select', function (e) {
    playCalling.setQuarter(e.params.data.id)
    quarter.setQuarter(e.params.data.id)
  })

  // pick referee
  const pickReferee = () => {
    playCalling.setReferee($("input[type=radio][name=referee]:checked").data('value'))
  }

  // evaluate value whenever violation click
  const pickPlayCall = () => {
    // const callAnalysis = $("input[type=radio][name=call_analysis]:checked").data('value')
    const position = $("input[type=radio][name=position]:checked").data('value')
    const zoneBox = $("input[type=radio][name=zone_box]:checked").data('value')
    const callType = $("input[type=radio][name=call_type]:checked").data('value')
    const iot = $("input[type=checkbox][name=iot]:checked").map((index, element) => {
      // console.log($(element).data('value'))
      return $(element).data('value')
    }).get()
    // console.log($("input[type=checkbox][name=iot]:checked").length)

    // playCalling.setCallAnalysis(callAnalysis)
    playCalling.setPosition(position)
    playCalling.setZoneBox(zoneBox)
    playCalling.setCallType(callType)
    playCalling.setIot(iot)
  }

  const pickCallAnalysis = () => {
    const callAnalysis = $("input[type=radio][name=call_analysis]:checked").data('value')

    if (callAnalysis.id == 3) {
      if (!playCalling.getCurrentPlayCalling().time) {
        playCalling.setTime(timer.getTime().formattedTime);
      }
    } else {
      if (timer.isRunning()) {
        playCalling.setTime(null);
      }
    }

    playCalling.setCallAnalysis(callAnalysis)
  }

  // referee picker handler
  $("input[type=radio][name=referee]").on("click", pickReferee)

  // violation picker handler
  $("input[type=radio][name=call_analysis]").on("click", pickCallAnalysis)
  $("input[type=radio][name=position]").on("click", pickPlayCall)
  $("input[type=radio][name=zone_box]").on("click", pickPlayCall)
  $("input[type=radio][name=call_type]").on("click", pickPlayCall)
  $("input[type=checkbox][name=iot]").on("click", pickPlayCall)

  // clear selection
  const clearSelection = () => {
    $("input[type=radio][name=referee]:checked").prop("checked", false);
    $("input[type=radio][name=call_analysis]:checked").prop("checked", false);
    $("input[type=radio][name=position]:checked").prop("checked", false);
    $("input[type=radio][name=zone_box]:checked").prop("checked", false);
    $("input[type=radio][name=call_type]:checked").prop("checked", false);
    $("input[type=checkbox][name=iot]:checked").prop("checked", false);

    playCalling.setReferee(null)
    playCalling.setTime(null)
    playCalling.setCallAnalysis(null)
    playCalling.setPosition(null)
    playCalling.setZoneBox(null)
    playCalling.setCallType(null)
    playCalling.setIot(null)
  }

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
    data: [...playCalling.getPlayCalling().values()],
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
      // {
      //   data: 'action',
      //   render: (data) => {
      //     return 'tes'
      //   }
      // },
      {
        data: 'quarter',
      },
      {
        data: 'referee.name',
      },
      {
        data: 'time',
      },
      {
        data: 'playCalling.callAnalysis.text',
      },
      {
        data: 'playCalling.position.text',
      },
      {
        data: 'playCalling.zoneBox.text',
      },
      {
        data: 'playCalling.callType.text',
      },
      {
        data: 'playCalling.iot',
        render: (data) => {
          const iot = data.map((value, index) => {
            return `<div> - ${value.text} </div>`
          })

          return iot.join(' ');
        }
      }
    ]
  });


  // var counter = 1
  // add row
  const addRow = (data) => {
    // console.log('added data', data)
    dt.row.add(data).draw(false);
    // counter++
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

  // add play calling
  $(document).on('click', '#add-play-calling', function () {
    playCalling.validate(
      (currentPlayCallingData) => {
        // add row
        addRow(currentPlayCallingData)

        // show message success
        showAlert('Berhasil ditambahkan', 'success')

        // clear selection
        clearSelection()
      }, (errorMessage) => {
        showAlert(errorMessage)
      })
  })

  // simpan play calling
  $(document).on('click', '#submit-play-calling', function () {
    // add play calling data to hidden input
    $('input[type=hidden][name=play_calling').val(JSON.stringify([...playCalling.getPlayCalling().values()]))
    // submit
    $('#form-play-calling').submit()
  })

})