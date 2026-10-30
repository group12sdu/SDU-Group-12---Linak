//headder file for the preset class
#ifndef PRESET_H
#define PRESET_H

namespace management{
    class Preset{
        private:
            int standing_height_;
            int sitting_height_;
        public:
            Preset(const int& standing_height, const int& sitting_height);
            ~Preset();
            void edit_preset(const int& standing_height, const int& sitting_height);
            int get_standing_height() const;
            int get_sitting_height() const;
    };
}
#endif