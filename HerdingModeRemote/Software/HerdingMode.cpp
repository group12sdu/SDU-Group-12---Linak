#include <string>
#include <cstdint>
#include <iostream>

#include "HerdingMode.h"
namespace sw = software;

sw::HerdingMode::HerdingMode(std::string& name, std::uint16_t& height)
    : _name{name}, _height{height}
{
    std::cout << "Herding mode: " << _name << ", initialised with height: " << _height;
}