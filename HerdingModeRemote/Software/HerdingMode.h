#ifndef HERDINGMODE_H
#define HERDINGMODE_H

#include <string>
#include <cstdint>

namespace software{
    class HerdingMode{
        public:
            const std::string _name;
            const std::uint16_t _height;
            HerdingMode(std::string& name, std::uint16_t& height);
            ~HerdingMode() = default;
    };
}
#endif