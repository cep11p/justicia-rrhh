import api from './api'


/**
 * Crea una nueva liquidación
 * @param {*} payload 
 * @returns {Promise<object>} - Datos de la liquidación creada
 */
export async function crearLiquidacionApi(payload) {
  const response = await api.post('/liquidacion/store', payload, {
    headers: {
      Authorization: `Bearer ${localStorage.getItem("token")}`,
    },
  });
  return response.data;
}

/**
 * Obtiene las liquidaciones con filtros opcionales
 * @param {string} globalSearch - Término de búsqueda global
 * @param {number} page - Número de página
 * @param {object} filtros - Filtros adicionales
 * @returns {Promise<object>} - Datos de las liquidaciones
 */
export const getLiquidaciones = async (globalSearch = '', page = 1, filtros = {}) => {
  try {
    const params = new URLSearchParams()
    
    // Agregar parámetros de búsqueda
    if (globalSearch) {
      params.append('global_search', globalSearch)
    }
    if (page > 1) {
      params.append('page', page)
    }
    
    // Agregar filtros adicionales
    if (filtros.periodo) {
      params.append('periodo', filtros.periodo)
    }
    if (filtros.cuil) {
      params.append('cuil', filtros.cuil)
    }
    if (filtros.legajo) {
      params.append('legajo', filtros.legajo)
    }
    
    const response = await api.get(`/liquidacion?${params.toString()}`)
    return response.data
  } catch (error) {
    console.error('Error al obtener liquidaciones:', error)
    throw error
  }
}

/**
   * Obtiene el detalle de una liquidación por ID
   * @param {number} id - ID de la liquidación
   * @returns {Promise<object>} - Datos de la liquidación
   */
export const getLiquidacionById = async (id) => {
  try {
    const response = await api.get(`/liquidacion/show/${id}`)
    return response.data.data
  } catch (error) {
    console.error(`Error al obtener la liquidación con id ${id}:`, error)
    throw error
  }
}

/**
 * Descarga el PDF de una liquidación
 * @param {number} id - ID de la liquidación
 * @returns {Promise<string>} - URL del PDF
 */
export const downloadLiquidacionPDF = async (id) => {
  try {
    const response = await api.get(`/liquidacion/view-to-pdf/${id}`, {
      responseType: 'blob'
    })

    // Crear un objeto Blob con el tipo correcto
    const file = new Blob([response.data], { type: 'application/pdf' })
    const fileURL = URL.createObjectURL(file)
    return fileURL
  } catch (error) {
    console.error(`Error al descargar PDF con id ${id}:`, error)
    throw error
  }
}

export function deleteLiquidacion(id) {
  return api.delete(`/liquidacion/delete/${id}`);
}


