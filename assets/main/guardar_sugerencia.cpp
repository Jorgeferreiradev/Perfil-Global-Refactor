#include <iostream>
#include <fstream>
#include <string>

int main() {
    std::string mensaje;
    std::ofstream archivo("sugerencias.txt", std::ios::app); // Modo "append"

    if (!archivo) {
        std::cerr << "Error al abrir el archivo" << std::endl;
        return 1;
    }

    std::cout << "Ingrese su sugerencia: ";
    std::getline(std::cin, mensaje); // Captura la entrada del usuario
    archivo << mensaje << std::endl; // Guarda la sugerencia en el archivo

    archivo.close();
    std::cout << "✅ Sugerencia guardada correctamente." << std::endl;

    return 0;
}