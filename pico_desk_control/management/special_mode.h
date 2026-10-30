//headder file for special mode class
#ifndef SPECIAL_MODE_H
#define SPECIAL_MODE_H

#include <string>

namespace management{
    class Special_mode{
        private:
            int height_;
            std::string name_;
        public:
            Special_mode(const int& height, const std::string& name_);
            ~Special_mode();
            void edit_special_mode(const int& height, const std::string& name);
            int get_height() const;
            std::string get_name() const;
    };
}
#endif