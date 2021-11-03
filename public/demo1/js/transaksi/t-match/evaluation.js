class Evaluation {

  evaluation = null
  currentEvaluation = {
    addedOn: null,
    quarter: null,
    wasit: null, // object wasit
    time: null, // string time
    evaluation: {
      callAnalysis: null,
      position: null,
      zoneBox: null,
      callType: null,
      iot: null
    } // object evaluation
  }

  constructor() {
    // set last evaluation to mapped evaluation
    const lastEvaluation = this.getLastEvaluation()
    if (lastEvaluation) {
      this.evaluation = lastEvaluation
      console.log('last evaluation', this.evaluation)
    } else {
      this.evaluation = new Map()
      console.log('new evaluation', this.evaluation)
    }
  }

  setAddedOn(value) { this.currentEvaluation.addedOn = value }
  setQuarter(value) { this.currentEvaluation.quarter = value }
  setWasit(value) { this.currentEvaluation.wasit = value }
  setTime(value) { this.currentEvaluation.time = value }
  setCallAnalysis(value) { this.currentEvaluation.evaluation.callAnalysis = value }
  setPosition(value) { this.currentEvaluation.evaluation.position = value }
  setZoneBox(value) { this.currentEvaluation.evaluation.zoneBox = value }
  setCallType(value) { this.currentEvaluation.evaluation.callType = value }
  setIot(value) { this.currentEvaluation.evaluation.iot = value }

  validate(validationSuccessCallback, validationErrorCallback) {
    // validation error
    if (!this.currentEvaluation.quarter) {
      const message = 'Error, quarter belum dipilih!'
      validationErrorCallback(message)
    }
    else if (!this.currentEvaluation.time) {
      const message = 'Error, pause timer terlebih dahulu!'
      validationErrorCallback(message)
    }
    else if (!this.currentEvaluation.wasit) {
      const message = "Error, pilih wasit terlebih dahulu"
      validationErrorCallback(message)
    }
    else if (!this.currentEvaluation.evaluation.callAnalysis) {
      const message = "Error, pilih call analysis terlebih dahulu"
      validationErrorCallback(message)
    }
    else if (!this.currentEvaluation.evaluation.position) {
      const message = "Error, pilih position terlebih dahulu"
      validationErrorCallback(message)
    }
    else if (!this.currentEvaluation.evaluation.zoneBox) {
      const message = "Error, pilih zone box terlebih dahulu"
      validationErrorCallback(message)
    }
    else if (!this.currentEvaluation.evaluation.callType) {
      const message = "Error, pilih call type dahulu"
      validationErrorCallback(message)
    }
    else if (!this.currentEvaluation.evaluation.iot) {
      const message = "Error, pilih iot terlebih dahulu"
      validationErrorCallback(message)
    }

    // validation success
    if (
      this.currentEvaluation.quarter &&
      this.currentEvaluation.time &&
      this.currentEvaluation.wasit &&
      this.currentEvaluation.evaluation.callAnalysis &&
      this.currentEvaluation.evaluation.position &&
      this.currentEvaluation.evaluation.zoneBox &&
      this.currentEvaluation.evaluation.callType &&
      this.currentEvaluation.evaluation.iot
    ) {
      // add current evaluation to mapped data
      this.evaluation.set(this.currentEvaluation.addedOn, this.currentEvaluation)

      // add to local storage
      this.setLastEvaluation()

      // callback success
      validationSuccessCallback(this.currentEvaluation)
    }
  }

  setLastEvaluation() {
    localStorage.setItem('evaluation', JSON.stringify([...this.evaluation.values()]))
  }

  getLastEvaluation() {
    const evaluation = localStorage.getItem('evaluation')
    if (!evaluation) return false
    const arrayEvaluation = JSON.parse(evaluation)
    // mapped array evaluation to hashmap
    const hashMap = new Map()
    arrayEvaluation.map((value, index) => {
      hashMap.set(value.addedOn, value)
    })
    return hashMap
  }

  getCurrentEvaluation() {
    return this.currentEvaluation
  }

  getEvaluation() {
    return this.evaluation
  }

  getEvaluationByKey(key) {
    return this.evaluation.get(key)
  }
}