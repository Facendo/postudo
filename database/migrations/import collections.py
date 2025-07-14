import collections

# --- Simulación de Sp (Solución Parcial) ---
def create_sp(alternativa, propiedad):
    """Crea una representación de la Solución Parcial."""
    return {"alternativa": alternativa, "propiedad": propiedad}

# --- Funciones que reemplazan los métodos de ProblemaSaltosPDR ---

def create_problema_saltos(num_list):
    """
    Inicializa el estado del problema de saltos.
    Equivalente al método estático create en Java.
    """
    numeros = list(num_list)  # lista normal
    al_reves = list(num_list)
    al_reves.reverse()  # lista invertida

    mundo_del_reves = 0  # 0: normal, 1: al revés
    pos_actual = 0

    return {
        "numeros": numeros,
        "al_reves": al_reves,
        "mundo_del_reves": mundo_del_reves,
        "pos_actual": pos_actual,
        "tipo": "Min"  # El tipo de optimización es Min (mínimo número de movimientos)
    }

def get_tipo(estado_problema):
    """Devuelve el tipo de problema (Min)."""
    return estado_problema["tipo"]

def size(estado_problema):
    """Calcula el tamaño restante para alcanzar el final."""
    return (len(estado_problema["numeros"]) - 1) - estado_problema["pos_actual"]

def es_caso_base(estado_problema):
    """Verifica si se ha alcanzado el caso base."""
    return (len(estado_problema["numeros"]) - 1) == estado_problema["pos_actual"]

def get_solucion_parcial_caso_base(estado_problema):
    """Devuelve la solución parcial para el caso base."""
    if estado_problema["mundo_del_reves"] == 0:
        return create_sp(0, 0.0)  # Alternativa 0, propiedad 0.0 (movimientos)
    return None

def get_sub_problema(estado_problema, alternativa):
    """
    Crea un nuevo estado de subproblema basado en una alternativa (salto).
    """
    nueva_posicion = estado_problema["pos_actual"] + alternativa
    
    # Crea una copia del estado actual para el subproblema
    nuevo_estado = estado_problema.copy()
    
    if nuevo_estado["mundo_del_reves"] == 0:
        nuevo_estado["pos_actual"] = nueva_posicion
    else:
        nuevo_estado["pos_actual"] = nueva_posicion
    
    return nuevo_estado

def get_solucion_parcial_por_alternativa(alternativa, sp_siguiente):
    """
    Calcula la solución parcial al tomar una alternativa.
    sp_siguiente es la solución parcial del subproblema.
    """
    return create_sp(alternativa, sp_siguiente["propiedad"] + 1.0)


def get_alternativas(estado_problema):
    """
    Genera las alternativas de salto posibles desde la posición actual.
    Esta función tiene la lógica de cambiar de "mundo" si se encuentra un 0.
    """
    alternativas = []
    
    a_usar = []
    if estado_problema["mundo_del_reves"] == 0:
        a_usar = estado_problema["numeros"]
    else:
        a_usar = estado_problema["al_reves"]

    nuevo_mundo_del_reves = estado_problema["mundo_del_reves"]
    
    if estado_problema["pos_actual"] < len(a_usar) and a_usar[estado_problema["pos_actual"]] == 0:
        if nuevo_mundo_del_reves == 0:
            nuevo_mundo_del_reves = 1
            a_usar = estado_problema["al_reves"]
        else:
            nuevo_mundo_del_reves = 0
            a_usar = estado_problema["numeros"]
    
    valor_actual = a_usar[estado_problema["pos_actual"]]
    
    for i in range(1, valor_actual + 1):
        if (estado_problema["pos_actual"] + i) >= len(a_usar):
            break
        alternativas.append(i)
        
    return alternativas, nuevo_mundo_del_reves


def get_solucion_reconstruida_caso_base(sp):
    """Reconstruye la solución para el caso base."""
    return [sp["alternativa"]]

def get_solucion_reconstruida_caso_recursivo(sp, s):
    """Reconstruye la solución en un paso recursivo."""
    res = list(s) if s is not None else []
    res.append(sp["alternativa"])
    return res

# --- Simulación de AlgoritmoPD (para Propósitos de Test) ---

def algoritmo_pd_ejecuta(problema_inicial_estado):
    """
    Simula la ejecución del algoritmo de programación dinámica.
    Implementa una versión memoizada (Top-Down) y muestra el proceso.
    """
    memo = {} # (pos_actual, mundo_del_reves) -> mejor_sp

    def resolver_subproblema(current_estado):
        pos_actual = current_estado["pos_actual"]
        mundo_del_reves = current_estado["mundo_del_reves"]
        estado_clave = (pos_actual, mundo_del_reves)
        
        print(f"\n--- Explorando subproblema: Posición {pos_actual}, Mundo {'Normal' if mundo_del_reves == 0 else 'Al Revés'} ---")

        if estado_clave in memo:
            print(f"  -> Resultado ya memoizado para {estado_clave}: {memo[estado_clave]['propiedad']} movimientos.")
            return memo[estado_clave]

        if es_caso_base(current_estado):
            sp_base = get_solucion_parcial_caso_base(current_estado)
            print(f"  -> ¡Caso base alcanzado! Solución parcial: {sp_base['propiedad']} movimientos.")
            memo[estado_clave] = sp_base
            return sp_base

        alternativas, nuevo_mundo_despues_cero_check = get_alternativas(current_estado)
        print(f"  -> Valor en posición {pos_actual}: {current_estado['numeros'][pos_actual] if mundo_del_reves == 0 else current_estado['al_reves'][pos_actual]}")
        print(f"  -> Alternativas posibles: {alternativas}")
        
        if not alternativas and not es_caso_base(current_estado):
            print("  -> Sin alternativas válidas para continuar desde aquí. Rama sin solución.")
            return None # No hay forma de avanzar desde esta posición

        mejor_sp = None
        
        for alt in alternativas:
            print(f"    Intentando salto de {alt} unidades.")
            temp_sub_estado = current_estado.copy()
            temp_sub_estado["mundo_del_reves"] = nuevo_mundo_despues_cero_check
            
            sub_problema = get_sub_problema(temp_sub_estado, alt)
            sp_siguiente = resolver_subproblema(sub_problema) # Llamada recursiva

            if sp_siguiente is not None:
                sp_actual = get_solucion_parcial_por_alternativa(alt, sp_siguiente)
                print(f"      Salto {alt} lleva a {sp_siguiente['propiedad'] + 1:.0f} movimientos totales desde aquí.")
                
                if mejor_sp is None or sp_actual["propiedad"] < mejor_sp["propiedad"]:
                    mejor_sp = sp_actual
                    print(f"      Nuevo mejor camino encontrado para {estado_clave}: Salto {alt}, con {mejor_sp['propiedad']:.0f} movimientos.")
            else:
                print(f"      Salto {alt} no conduce a una solución válida.")
                    
        memo[estado_clave] = mejor_sp
        return mejor_sp

    # Iniciar la resolución desde el estado inicial
    print("--- INICIANDO ALGORITMO DE PROGRAMACIÓN DINÁMICA ---")
    mejor_sp_final = resolver_subproblema(problema_inicial_estado)
    print("\n--- FIN DE LA EXPLORACIÓN ---")

    # Reconstrucción de la solución (ruta óptima)
    solucion_saltos = []
    current_estado_reconstruccion = problema_inicial_estado.copy()
    
    print("\n--- RECONSTRUYENDO LA SOLUCIÓN ÓPTIMA ---")
    while not es_caso_base(current_estado_reconstruccion):
        pos_actual_rec = current_estado_reconstruccion["pos_actual"]
        mundo_del_reves_rec = current_estado_reconstruccion["mundo_del_reves"]
        
        alternativas_rec, nuevo_mundo_despues_cero_rec = get_alternativas(current_estado_reconstruccion)
        
        mejor_alternativa_para_paso = None
        min_propiedad_para_paso = float('inf')
        
        print(f"  Desde Posición {pos_actual_rec}, Mundo {'Normal' if mundo_del_reves_rec == 0 else 'Al Revés'}:")
        
        # Considerar el efecto del '0' en el mundo para la reconstrucción
        if (mundo_del_reves_rec == 0 and current_estado_reconstruccion["numeros"][pos_actual_rec] == 0) or \
           (mundo_del_reves_rec == 1 and current_estado_reconstruccion["al_reves"][pos_actual_rec] == 0):
            print(f"    ¡Valor 0 en la posición {pos_actual_rec}! El mundo cambiará a {'Al Revés' if nuevo_mundo_despues_cero_rec == 1 else 'Normal'}.")
            
        for alt in alternativas_rec:
            temp_sub_estado_rec = current_estado_reconstruccion.copy()
            temp_sub_estado_rec["mundo_del_reves"] = nuevo_mundo_despues_cero_rec
            
            sub_problema_siguiente_rec = get_sub_problema(temp_sub_estado_rec, alt)
            
            estado_clave_siguiente_rec = (sub_problema_siguiente_rec["pos_actual"], sub_problema_siguiente_rec["mundo_del_reves"])
            sp_siguiente_memo_rec = memo.get(estado_clave_siguiente_rec)
            
            if sp_siguiente_memo_rec is not None:
                propiedad_total_rec = sp_siguiente_memo_rec["propiedad"] + 1.0
                
                if propiedad_total_rec < min_propiedad_para_paso:
                    min_propiedad_para_paso = propiedad_total_rec
                    mejor_alternativa_para_paso = alt
        
        if mejor_alternativa_para_paso is not None:
            solucion_saltos.append(mejor_alternativa_para_paso)
            print(f"    Elegido el salto: {mejor_alternativa_para_paso}. Propiedad total hasta ahora: {min_propiedad_para_paso:.0f}")
            
            # Aplicar el cambio de mundo si se encontró un 0 en la posición actual
            if (current_estado_reconstruccion["mundo_del_reves"] == 0 and current_estado_reconstruccion["numeros"][pos_actual_rec] == 0) or \
               (current_estado_reconstruccion["mundo_del_reves"] == 1 and current_estado_reconstruccion["al_reves"][pos_actual_rec] == 0):
                current_estado_reconstruccion["mundo_del_reves"] = nuevo_mundo_despues_cero_rec
            
            current_estado_reconstruccion["pos_actual"] += mejor_alternativa_para_paso
        else:
            print("  Error: No se encontró la mejor alternativa para reconstruir la solución. Esto no debería pasar si hay una solución.")
            break
    print("--- RECONSTRUCCIÓN COMPLETA ---")
            
    return mejor_sp_final, solucion_saltos


# --- Test (similar a main en Java) ---

if __name__ == "__main__":
    # configuración de parámetros
    numeros_test = [2, 4, 2, 0, 2, 3, 2, 4, 3, 3, 4]
    # numeros_test = [2, 3, 1, 0, 1] # Otro ejemplo
    
    # instanciamos el problema (creamos el diccionario de estado)
    problema_estado = create_problema_saltos(numeros_test)
    
    # ejecución del algoritmo PD
    mejor_sp, solucion_reconstruida = algoritmo_pd_ejecuta(problema_estado)
    
    num_movimientos = int(mejor_sp["propiedad"]) if mejor_sp else 0

    print("\n" + "="*50)
    print("RESUMEN DE LA SOLUCIÓN FINAL")
    print("="*50)
    print(f"Menor número de movimientos: {num_movimientos}")
    print(f"Saltos: {solucion_reconstruida}")
    print("="*50)