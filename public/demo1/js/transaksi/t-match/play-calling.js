class PlayCalling {

  playCalling = new Map()
  currentPlayCalling = {
    addedOn: null,
    quarter: null,
    referee: null, // object referee
    time: null, // string time
    playCalling: {
      callAnalysis: null,
      position: null,
      zoneBox: null,
      callType: null,
      iot: null
    } // object play calling
  }

  constructor() {
    // set last play calling to mapped play calling
    const lastPlayCalling = this.getLastPlayCalling()
    if (lastPlayCalling) {
      this.playCalling = lastPlayCalling
      console.log('last play calling', this.playCalling)
    } else {
      this.playCalling = new Map()
      console.log('new play calling', this.playCalling)
    }
  }

  setAddedOn(value) { this.currentPlayCalling.addedOn = value }
  setQuarter(value) { this.currentPlayCalling.quarter = value }
  setReferee(value) { this.currentPlayCalling.referee = value }
  setTime(value) { this.currentPlayCalling.time = value }
  setCallAnalysis(value) { this.currentPlayCalling.playCalling.callAnalysis = value }
  setPosition(value) { this.currentPlayCalling.playCalling.position = value }
  setZoneBox(value) { this.currentPlayCalling.playCalling.zoneBox = value }
  setCallType(value) { this.currentPlayCalling.playCalling.callType = value }
  setIot(value) { this.currentPlayCalling.playCalling.iot = value }

  validate(validationSuccessCallback, validationErrorCallback) {
    // validation error
    if (!this.currentPlayCalling.quarter) {
      const message = 'Error, quarter belum dipilih!'
      validationErrorCallback(message)
    }
    else if (!this.currentPlayCalling.time) {
      const message = 'Error, pause timer terlebih dahulu!'
      validationErrorCallback(message)
    }
    else if (!this.currentPlayCalling.referee) {
      const message = "Error, pilih wasit terlebih dahulu"
      validationErrorCallback(message)
    }
    else if (!this.currentPlayCalling.playCalling.callAnalysis) {
      const message = "Error, pilih call analysis terlebih dahulu"
      validationErrorCallback(message)
    }
    else if (!this.currentPlayCalling.playCalling.position) {
      const message = "Error, pilih position terlebih dahulu"
      validationErrorCallback(message)
    }
    else if (!this.currentPlayCalling.playCalling.zoneBox) {
      const message = "Error, pilih zone box terlebih dahulu"
      validationErrorCallback(message)
    }
    else if (!this.currentPlayCalling.playCalling.callType) {
      const message = "Error, pilih call type dahulu"
      validationErrorCallback(message)
    }
    // else if (!this.currentPlayCalling.playCalling.iot || this.currentPlayCalling.playCalling.iot.length == 0) {
    //   const message = "Error, pilih iot terlebih dahulu"
    //   validationErrorCallback(message)
    // }

    // validation success
    if (
      this.currentPlayCalling.quarter &&
      this.currentPlayCalling.time &&
      this.currentPlayCalling.referee &&
      this.currentPlayCalling.playCalling.callAnalysis &&
      this.currentPlayCalling.playCalling.position &&
      this.currentPlayCalling.playCalling.zoneBox &&
      this.currentPlayCalling.playCalling.callType 
      // &&
      // this.currentPlayCalling.playCalling.iot.length > 0
    ) {
      // added on
      this.setAddedOn(Date.now())
      // add current play calling to mapped data
      this.playCalling.set(this.currentPlayCalling.addedOn, { ...this.currentPlayCalling, playCalling: { ...this.currentPlayCalling.playCalling } })
      // add to local storage
      this.setLastPlayCalling()

      // callback success
      validationSuccessCallback(this.currentPlayCalling)
    }
  }

  setLastPlayCalling() {
    localStorage.setItem('playCalling', JSON.stringify([...this.playCalling.values()]))
  }

  getLastPlayCalling() {
    const playCalling = localStorage.getItem('playCalling')
    if (!playCalling) return false
    const arrayPlayCalling = JSON.parse(playCalling)
    // return [...arrayPlayCalling]
    // mapped array play calling to hashmap
    const hashMap = new Map()
    arrayPlayCalling.map((value, index) => {
      hashMap.set(value.addedOn, value)
    })
    return hashMap
  }

  getCurrentPlayCalling() {
    return this.currentPlayCalling
  }

  getPlayCalling() {
    return this.playCalling
  }

  // getPlayCallingByKey(key) {
  //   return this.playCalling.get(key)
  // }

  // setPlayCalling(data) {
  //   this.playCalling.set(data.addedOn, data)
  //   console.log(this.playCalling)
  // }

  clear() {
    this.playCalling = new Map()
    this.currentPlayCalling = {
      addedOn: null,
      quarter: null,
      referee: null, // object referee
      time: null, // string time
      playCalling: {
        callAnalysis: null,
        position: null,
        zoneBox: null,
        callType: null,
        iot: null
      } // object play calling
    }
    localStorage.setItem('playCalling', null)
  }
}