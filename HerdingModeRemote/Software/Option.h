#ifndef OPTION_H
#define OPTION_H

#include <string>

namespace software{
    class Option{
        private:
            char option_[11]{"0000000000"};
        public:
            explicit Option(std::string option);
            ~Option() = default;
            std::string String();
    };
}
#endif