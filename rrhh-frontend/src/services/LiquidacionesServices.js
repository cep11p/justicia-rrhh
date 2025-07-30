import api from './api'

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


